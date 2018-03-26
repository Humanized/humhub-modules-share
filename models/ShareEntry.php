<?php

namespace humhub\modules\share\models;

use Yii;
use yii\helpers\ArrayHelper;
use humhub\modules\content\models\Content;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use humhub\modules\user\models\User;

use humhub\modules\share\widgets\WallEntry;


/**
 * A ShareEntry class is used to repost another ContentActiveRecord on
 * spaces mentioned by the user in this object.
 *
 * This is the model class for table "share".
 *
 * @property integer $id
 * @property Content $entry
 * @property string $message
 */
class ShareEntry extends ContentActiveRecord
{
    /**
     * @inheritdoc
     */
    public $wallEntryClass = WallEntry::class;


    private $_entryContent;

    /**
     *  @return Content shared by this entry or null (if deleted)
     */
    public function getEntryContent() {
        if(!isset($this->_entryContent))
            $this->_entryContent = Content::findOne($this->entry);
        return $this->_entryContent;
    }


    private $_entry;

    /**
     *  @return ContentActiveRecord shared by this entry or null (if deleted)
     */
    public function getEntry() {
        if(!isset($this->_entry)) {
            $content = $this->entryContent;
            if($content)
                $this->_entry = $content->getPolymorphicRelation();
            else
                $this->_entry = null;
        }
        return $this->_entry;
    }


    /**
     *  Return the related object based on content. If content
     *  is for a SharedEntry, return entry's target
     */
    static function realTarget($content) {
        if($content->object_model == ShareEntry::className())
            return $content->getPolymorphicRelation()->entryContent;
        return $content;
    }


    /**
     *  Return ActiveQuery of all SharedEntry items for the given
     *  Content
     */
    public static function findFor($content)
    {
        $content = ShareEntry::realTarget($content);
        return ShareEntry::find()->where(['entry' => $content]);
    }

    /**
     *  Delete ShareEntry items related to this content
     */
    public static function deleteFor($content)
    {
        $mentions = ShareEntry::find()->where([
            'entry' => $content->id
        ])->all();

        foreach($mentions as $mention) {
            $mention->delete();
        }
    }


    /**
     *  Create entries for the given content on the given
     *  spaces. User perms are checked, cauz' we are cool
     *
     *  @param Content
     *  @param [] $spaceSelector condition to use in a Where clause in
     *       order to select spaces
     *  @param string $message attach this message to the created instances
     *  @return [Space] spaces on which new instances have been created
     */
    public static function createFor(
        $content, $message, $spacesSelector
    )
    {
        if($message)
            $message = strip_tags($message);

        $user = Yii::$app->user->identity;
        $spaces = Membership::getUserSpaceQuery($user)->distinct()
            ->andWhere(['space.status' => Space::STATUS_ENABLED])
            ->andWhere(['!=', 'id', $content->container->id])
            ->andWhere($spacesSelector);

        // exclude spaces where there is yet an entry
        $exclude = [];
        foreach(ShareEntry::findFor($content)->all() as $entry)
            $exclude[] = $entry->content->contentcontainer_id;
        if($exclude)
            $spaces = $spaces->andWhere([
                'not in', 'contentcontainer_id', $exclude
            ]);

        // create!
        $spaces = $spaces->all();
        foreach ($spaces as $space) {
            // if(!$space->can('write')) TODO
            $entry = new ShareEntry();
            $entry->entry = $content->id;
            if($message)
                $entry->message = $message;
            $entry->content->container = $space;
            $entry->content->visibility = $content->visibility;
            $entry->save();
        }
        return $spaces;
    }


    /**
     *  Parse a given text and create/update mentions according
     *  to it.
     *
     *  If content is updated, extra tasks are run in order to clean-up
     *  old mentions and avoid recreating existing mentions
     *
     *  @param Content $content
     *  @param string $text text to search mentions in
     *  @param bool update content is being updated (not created)
     *
     *  @return [Space] An array of spaces for which a mention has been
     *          created (not updated)
     */
    public static function parse($content, $text, $update = False)
    {
        $content = ShareEntry::realTarget($content);
        $user = User::findOne($content->updated_by);

        // mentions & spaces
        preg_match_all('@\@\-s([\w\-]*?)($|\s|\.)@', $text, $mentions);
        $mentions = array_unique($mentions[1]);
        if(!count($mentions))
            return [];
        return $this->createFor($content, null, ['guid' => $mentions ]);
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'share_entry';
    }
}

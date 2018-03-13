<?php

namespace humhub\modules\space_mentioning\models;

use Yii;
use yii\helpers\ArrayHelper;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "space_mention".
 *
 * FIXME: use polymorphic relation behaviour?
 *
 * @property integer $id
 * @property integer $post
 */
class SpaceMention extends ContentActiveRecord
{
    /**
     * @inheritdoc
     */
    public $wallEntryClass = 'humhub\modules\space_mentioning\widgets\WallEntry';


    /**
     *  Delete SpaceMentions related to this content
     */
    public static function deleteFor($content)
    {
        $content = $content->content;
        $mentions = SpaceMention::find()->where([
            'related' => $content->id
        ])->all();

        foreach($mentions as $mention) {
            $mention->delete();
        }
    }

    /**
     *  Parse a given text and create/update mentions according
     *  to it.
     *
     *  If content is updated, extra tasks are run in order to clean-up
     *  old mentions and avoid recreating existing mentions
     *
     *  @param \humhub\modules\content\models\ContentActiveRecord $content
     *  @param string $text text to search mentions in
     *  @param bool update content is being updated (not created)
     */
    public static function parse($content, $text, $update = False)
    {
        $content = $content->content;
        $user = User::findOne($content->updated_by);

        // mentions & spaces
        preg_match_all('@\@\-s([\w\-]*?)($|\s|\.)@', $text, $mentions);
        $mentions = array_unique($mentions[1]);

        $spaces = Membership::getUserSpaceQuery($user)->distinct()
            ->andWhere(['!=', 'id', $content->container->id])
            ->andWhere(['in', 'guid', $mentions]);

        if($update) {
            $ids = ArrayHelper::getColumn($spaces->all(), 'contentcontainer_id');
            $mentions = SpaceMention::find()->where([
                'related' => $content->id
            ])->all();

            $done = [];
            foreach($mentions as $mention) {
                if(in_array($mention->content->contentcontainer_id, $ids))
                    $done[] = $mention->content->contentcontainer_id;
                else
                    $mention->delete();
            }

            // update list of spaces
            $spaces = $spaces->andWhere([
                'not in', 'contentcontainer_id', $done
            ]);
        }

        // create new records
        foreach ($spaces->all() as $space) {
            $mention = new SpaceMention();
            $mention->related = $content->id;
            $mention->content->container = $space;
            $mention->content->visibility = $content->visibility;
            $mention->save();
        }
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'space_mention';
    }
}

<?php

namespace humhub\modules\space_mentioning\widgets;

use humhub\modules\content\models\Content;

class WallEntry extends \humhub\modules\content\widgets\WallEntry
{
    public $related;
    public $wallEntryLayout = "@humhub/modules/space_mentioning/widgets/views/wallEntry.php";

    /**
     * @inheritdoc
     */
    public function run()
    {
        if(!$this->related)
            return "The original message has been removed";

        return $this->related->getWallOut([
            'showFiles' => false,
            'renderControls' => false,
            'renderAddons' => false,
        ]);
    }


    public function getWallEntryViewParams()
    {
        $content = Content::find()->where([
            'id' => $this->contentObject->related
        ])->one();

        if($content)
            $this->related = $content->getPolymorphicRelation();

        $params = parent::getWallEntryViewParams();
        $params["related"] = $this->related;
        return $params;
    }

}

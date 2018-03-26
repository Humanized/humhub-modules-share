<?php

namespace humhub\modules\share\widgets;

use humhub\modules\content\models\Content;

class WallEntry extends \humhub\modules\content\widgets\WallEntry
{
    public $entry;
    public $wallEntryLayout = "@share/widgets/views/wallEntry.php";

    /**
     * @inheritdoc
     */
    public function run()
    {
        if(!$this->entry)
            return "The original content has been removed";

        return $this->entry->getWallOut([
            'showFiles' => false,
            'renderControls' => false,
            'renderAddons' => false,
        ]);
    }


    public function getWallEntryViewParams()
    {
        $this->entry = $this->contentObject->getEntry();
        $params = parent::getWallEntryViewParams();
        $params["entry"] = $this->entry;
        return $params;
    }

}

<?php

namespace humhub\modules\share\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

use humhub\modules\content\models\Content;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;

use humhub\modules\share\models\ShareEntry;


class ShareModalForm extends Model
{
    public $id;
    public $message;
    public $spaces;


    private $_object;

    public function getObject() {
        if(!isset($this->_object)) {
            $object = Content::findOne($this->id);
            if($object)
                $object = ShareEntry::realTarget($object);
            $this->_object = ($object && $object->canView()) ? $object : null;
        }
        return $this->_object;
    }

    public function rules()
    {
        return [
            ['id', 'integer'],
            ['message', 'string'],
            ['spaces', 'each', "rule" => [ 'integer' ] ],
        ];
    }

    public function save()
    {
        if(!$this->validate() || !$this->object)
            return false;

        return true;
    }

    /**
     *  @return ActiveQuery of spaces accessible to user
     */
    public function allowedSpaces($user = null)
    {
        if(!$user)
            $user = Yii::$app->user->identity;
        return Membership::getUserSpaceQuery($user)->distinct()
            ->andWhere(['space.status' => Space::STATUS_ENABLED])
            ->andWhere(['!=', 'id', $this->object->container->id])
            ;
    }
}







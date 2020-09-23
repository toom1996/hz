<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class BaseModel
 * @package common\models\common
 * @author jianyan74 <751393839@qq.com>
 */
class BaseModel extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

}
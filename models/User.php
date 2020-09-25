<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\User as BaseUser;
/**
 * This is the model class for table "{{%userprofile}}".
 *
 * @property int $id
 * @property string|null $username 用户名
 * @property string|null $password 密码
 * @property string $token token值
 * @property string $create_datetime 创建时间
 * @property string $head_portrait 头像
 * @property int $is_update_pwd 是否修改密码
 * @property int|null $role_id 角色
 * @property int|null $inviter_id 父账户
 * @property string|null $name 微信昵称
 * @property string|null $openid 微信公众号openid
 * @property int $subscribe 是否关注公众号
 * @property string|null $city 城市
 * @property string|null $country 国家
 * @property string|null $province 省份
 * @property int|null $sex 性别
 * @property string|null $login_timestamp 登录时间戳
 * @property int $number_child_users 可以创建几个子用户
 * @property int $small_program_number 可以创建几个小程序
 * @property int $login_type 登录类型1:微信扫码登录,2:叮咚营销宝
 * @property int|null $ding_dong_marketing_treasure_user_id 叮咚营销宝
 * @property string $select_template_list 可查看模板ID列表
 * @property string|null $company_name 公司名称
 * @property string|null $remark 备注信息
 * @property string|null $last_login_datetime 最后登录时间
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%userprofile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'create_datetime', 'head_portrait', 'is_update_pwd', 'subscribe', 'number_child_users', 'small_program_number', 'login_type', 'select_template_list'], 'required'],
            [['create_datetime', 'last_login_datetime'], 'safe'],
            [['is_update_pwd', 'role_id', 'inviter_id', 'subscribe', 'sex', 'number_child_users', 'small_program_number', 'login_type', 'ding_dong_marketing_treasure_user_id'], 'integer'],
            [['select_template_list', 'remark'], 'string'],
            [['username', 'password', 'token', 'name', 'city', 'country', 'province'], 'string', 'max' => 128],
            [['head_portrait', 'company_name'], 'string', 'max' => 256],
            [['openid', 'login_timestamp'], 'string', 'max' => 64],
            [['inviter_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['inviter_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'token' => 'token值',
            'create_datetime' => '创建时间',
            'head_portrait' => '头像',
            'is_update_pwd' => '是否修改密码',
            'role_id' => '角色',
            'inviter_id' => '父账户',
            'name' => '微信昵称',
            'openid' => '微信公众号openid',
            'subscribe' => '是否关注公众号',
            'city' => '城市',
            'country' => '国家',
            'province' => '省份',
            'sex' => '性别',
            'login_timestamp' => '登录时间戳',
            'number_child_users' => '可以创建几个子用户',
            'small_program_number' => '可以创建几个小程序',
            'login_type' => '登录类型1:微信扫码登录,2:叮咚营销宝',
            'ding_dong_marketing_treasure_user_id' => '叮咚营销宝',
            'select_template_list' => '可查看模板ID列表',
            'company_name' => '公司名称',
            'remark' => '备注信息',
            'last_login_datetime' => '最后登录时间',
        ];
    }

    /**
     * Gets query for [[Articleclasses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticleclasses()
    {
        return $this->hasMany(Articleclass::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Baidusmallprogrammanagements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBaidusmallprogrammanagements()
    {
        return $this->hasMany(Baidusmallprogrammanagement::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Businesscards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesscards()
    {
        return $this->hasMany(Businesscard::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Clientapplets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientapplets()
    {
        return $this->hasMany(Clientapplet::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Compomentlibraries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompomentlibraries()
    {
        return $this->hasMany(Compomentlibrary::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Compomentlibraryclasses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompomentlibraryclasses()
    {
        return $this->hasMany(Compomentlibraryclass::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Customerofficialnumbers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerofficialnumbers()
    {
        return $this->hasMany(Customerofficialnumber::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Forms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForms()
    {
        return $this->hasMany(Form::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Invitethechildren]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvitethechildren()
    {
        return $this->hasMany(Invitethechild::className(), ['child_id' => 'id']);
    }

    /**
     * Gets query for [[Invitethechildren0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvitethechildren0()
    {
        return $this->hasMany(Invitethechild::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Messageinforms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageinforms()
    {
        return $this->hasMany(Messageinform::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Pages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Pagegroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPagegroups()
    {
        return $this->hasMany(Pagegroup::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Permissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permissions::className(), ['oper_user_id' => 'id']);
    }

    /**
     * Gets query for [[Photolibraries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhotolibraries()
    {
        return $this->hasMany(Photolibrary::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Photolibrarygroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhotolibrarygroups()
    {
        return $this->hasMany(Photolibrarygroup::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Roles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Servicetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicetables()
    {
        return $this->hasMany(Servicetable::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Templates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(Template::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Templateclasses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemplateclasses()
    {
        return $this->hasMany(Templateclass::className(), ['create_user_id' => 'id']);
    }

    /**
     * Gets query for [[Transfers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers()
    {
        return $this->hasMany(Transfer::className(), ['by_connecting_people_id' => 'id']);
    }

    /**
     * Gets query for [[Transfers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers0()
    {
        return $this->hasMany(Transfer::className(), ['speak_to_people_id' => 'id']);
    }

    /**
     * Gets query for [[Inviter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInviter()
    {
        return $this->hasOne(User::className(), ['id' => 'inviter_id']);
    }

    /**
     * Gets query for [[Userprofiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasMany(User::className(), ['inviter_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //TODO
        return static::findOne([
            'token' => $token
        ]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     *
     * @return string
     */
    public function getHeadPortrait()
    {
        return $this->head_portrait;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}

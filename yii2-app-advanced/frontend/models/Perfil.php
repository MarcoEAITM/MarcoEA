<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

use common\models\User;

use yii\helpers\Url;

use yii\helpers\Html;

use yii\helpers\ArrayHelper;

use yii\db\Expression;

/**
 * This is the model class for table "perfil".
 *
 * @property int $id
 * @property int $user_id
 * @property string $nombre
 * @property string $apellido
 * @property string $fecha_nacimiento
 * @property int $genero_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Genero $genero
 */
class Perfil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perfil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'nombre', 'apellido', 'fecha_nacimiento', 'genero_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'genero_id'], 'integer'],
            [['nombre', 'apellido'], 'string'],
            [['genero_id'],'in', 'range'=>array_keys($this->getGeneroLista())],
            [['fecha_nacimiento'], 'date', 'format'=>'php:Y-m-d'],
            
            [['fecha_nacimiento', 'created_at', 'updated_at'], 'safe'],
            [['genero_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genero::className(), 'targetAttribute' => ['genero_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'generoNombre' => Yii::t('app', 'Genero'),

            'userLink' => Yii::t('app', 'User'),

            'perfilIdLink' => Yii::t('app', 'Perfil'),
            
            'user_id' => 'User ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'genero_id' => 'Genero ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Genero]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenero()
    {
        return $this->hasOne(Genero::className(), ['id' => 'genero_id']);
    }
    
    /**

        * behaviors

        */



    public function behaviors()

    {

        return [

            'timestamp' => [

            'class' => 'yii\behaviors\TimestampBehavior',

            'attributes' => [

                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],

                                ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                            ],

            'value' => new Expression('NOW()'),

                           ],

               ];

    }
    
    /**

 * @return \yii\db\ActiveQuery

 */



public function getGeneroNombre()

{

    return $this->genero->genero_nombre;

}

/**

 * get lista de generos para lista desplegable

 */



public static function getGeneroLista()

{

    $droptions = Genero::find()->asArray()->all();

    return ArrayHelper::map($droptions, 'id', 'genero_nombre');

}



/**

 * @return \yii\db\ActiveQuery

 */

public function getUser()

{

    return $this->hasOne(User::className(), ['id' => 'user_id']);

}



/**

 * @get Username

 */

public function getUsername()

{

    return $this->user->username;

}

/**

 * @getUserId

 */

public function getUserId()

{

    return $this->user ? $this->user->id : 'none';

}



/**

 * @getUserLink

 */



public function getUserLink()

{

    $url = Url::to(['user/view', 'id'=>$this->UserId]);

    $opciones = [];

    return Html::a($this->getUserName(), $url, $opciones);

}

/**

 * @getProfileLink

 */



public function getPerfilIdLink()

{

    $url = Url::to(['perfil/update', 'id'=>$this->id]);

    $opciones = [];

    return Html::a($this->id, $url, $opciones);

}
}

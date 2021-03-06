<?php

namespace app\modules\lrm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\lrm\models\InteractionNote;

/**
 * InteractionNoteSearch represents the model behind the search form about `app\modules\lrm\models\InteractionNote`.
 */
class InteractionNoteSearch extends InteractionNote
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'personId', 'appraisal', 'createdAt', 'updatedAt'], 'integer'],
            [['text', 'date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InteractionNote::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'personId' => $this->personId,
            'appraisal' => $this->appraisal,
            'date' => $this->date,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}


$mo = new MatrixOperator();
      $nn = new NeuralNetworks($mo);
      $plt = new Plot(null, $mo);

      $datasetdir = '../res/datasets';
      $datasetfile = $datasetdir . '/train/train.csv';
      $datasettest = $datasetdir . '/test/test.csv';
      $datasetdirtrain = $datasetdir . '/train';
      $savemodel = __DIR__ . '/res/model/tickets_model.json';
      if (!file_exists($savemodel)) {
         if (!file_exists($datasetfile)) {
            try {
               mkdir($datasetdir, 0777, true);
            } catch (Exception $e) {
               echo "Error creating directory: " . $e->getMessage();
            }
         }
         if (!file_exists($datasetfile)) {
            echo "Dataset not found";
         }
         #obtain the classes from the 3rd column of the dataset
         $contents = file_get_contents($datasetfile);
         $lines = explode("\n", $contents);
         $numExamples = count($lines);
         unset($contents);
         $trim = function ($w) {
            return trim($w);
         };
         foreach ($lines as $line) {
            if ($numExamples !== null) {
               $numExamples--;
               if ($numExamples < 0)
                  break;
            }
            #split the line by comma knowing that text begins with char "
            $blocks = preg_split('/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $line);
            #skip the headers
            if ($blocks[0] === '' || $blocks[1] === 'Title' || $blocks[2] === 'Resolution' || $blocks[3] === 'class')
               continue;
            $class = $blocks[3];
            $incident = $blocks[1];
            $solution = $blocks[2];

            $classnames[] = $class;
            $incidents[] = $incident;
            $solutions[] = $solution;
         }
         #remove duplicates and strange values
         $classnames = array_unique($classnames);

      /*******
       * Class NDArrayDataset
       * public function __construct(
            object $mo,
            array|NDArray $inputs,
            NDArray $tests=null,
            int $batch_size=null,
            bool $shuffle=null,
            DatasetFilter $filter=null,
        )
       */

      $incidents_db = $this->getIncidents();

      $train_labels_array = $mo->array($classnames);
      $test_labels_array = $mo->array($incidents);
      $test_inputs_array = $mo->array($incidents_db['incident_content']);
      $train_inputs_array = $mo->array($incidents);
      $train_labels = $mo->la()->astype($train_labels_array, NDArray::float32);
      $test_labels = $mo->la()->astype($test_labels_array, NDArray::float32);
      $test_inputs = $mo->la()->astype($test_inputs_array, NDArray::float32);
      $train_inputs = $mo->la()->astype($train_inputs_array, NDArray::float32);
      #the tokenizer must be created from the train data
      #the train data is the incidents is not type TextClassifiedDataset
      $tokenizer = $nn->data->TextClassifiedDataset($datasetdirtrain)->getTokenizer();

         $savedata = [
            $tokenizer->save(),
            $classnames,
            $mo->serializeArray([
               $train_inputs,
               $train_labels,
               $test_inputs,
               $test_labels
            ]),
         ];
         file_put_contents($savemodel, serialize($savedata));
      } else {
         [
            $tokenizer_data,
            $classnames,
            $tensors,
         ] = unserialize(file_get_contents($savemodel));
         $tokenizer = $nn->data->TextClassifiedDataset($datasetdir . '/train')->getTokenizer();
         $tokenizer->load($tokenizer_data);
         [
            $train_inputs,
            $train_labels,
            $test_inputs,
            $test_labels
         ] = $mo->unserializeArray($tensors);
      }
      $train_labels = $mo->la()->astype($train_labels, NDArray::float32);
      $test_labels = $mo->la()->astype($test_labels, NDArray::float32);
      echo implode(',', $train_inputs->shape()) . "\n";
      echo implode(',', $train_labels->shape()) . "\n";
      echo implode(',', $test_inputs->shape()) . "\n";
      echo implode(',', $test_labels->shape()) . "\n";
      $total_size = count($train_inputs);
      $train_size = (int)floor($total_size * 0.9);
      $val_inputs = $train_inputs[R($train_size, $total_size)];
      $val_labels = $train_labels[R($train_size, $total_size)];
      $train_inputs = $train_inputs[R(0, $train_size)];
      $train_labels = $train_labels[R(0, $train_size)];

      echo "device type: " . $nn->deviceType() . "\n";
      $modelFilePath = __DIR__ . '/basic-text-classification.model';

      if (file_exists($modelFilePath)) {
         echo "loading model ...\n";
         $model = $nn->models()->loadModel($modelFilePath);
         $model->summary();
      } else {
         $inputlen = $train_inputs->shape()[1];
         echo "creating model ...\n";
         $model = $nn->models()->Sequential([
            $nn->layers()->Embedding(
               $inputDim = count($tokenizer->getWords()),
               $outputDim = 16,
               input_length: $inputlen
            ),
            $nn->layers()->GlobalAveragePooling1D(),
            $nn->layers()->Dense(
               $units = 1,
               activation: 'sigmoid'
            ),
         ]);

         $model->compile(
            loss: $nn->losses->BinaryCrossEntropy(),
            optimizer: 'adam',
         );
         $model->summary();
         echo "training model ...\n";
         $history = $model->fit(
            $train_inputs,
            $train_labels,
            epochs: 10,
            batch_size: 64,
            validation_data: [$val_inputs, $val_labels],
         );
         $model->save($modelFilePath);
      }
      $model->evaluate(
         $test_inputs,
         $test_labels,
         batch_size: 64,
         verbose: 1,

      );
      return "Hola";
   }
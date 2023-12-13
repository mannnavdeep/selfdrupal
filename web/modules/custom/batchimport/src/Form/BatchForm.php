<?php
 
namespace Drupal\batchimport\Form;
 
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FileSystemInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
 
class BatchForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return 'batch_import_user_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#prefix'] = '<p>This form will upload content type fields From Csv</p>';

    $form['import_file'] = [
        '#title' => $this->t('Upload file'),
        '#type' => 'managed_file',
        '#description' => 'Upload CSV file for import',
        '#upload_validators' => [
          'file_validate_extensions' => ['csv'],
        ],
        '#upload_location' => 'public://content_imports',
      ];

    $form['actions'] = array(
      '#type' => 'actions',
      'submit' => array(
        '#type' => 'submit',
        '#value' => 'Proceed',
      ),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uploadedFile = $form_state->getValue('import_file');
    $file = File::load($uploadedFile[0]);
    $uri = $file->getFileUri();
    if (($fp = @fopen($uri, 'r')) !== FALSE) 
    {
        while (($row = fgetcsv($fp)) !== FALSE) 
        {
            $data[] = $row;
        }  
    } 
    $total = count($data);
    $batch = [
      'title' => t('Importing Nodes'),
      'operations' => [],
      'init_message' => t('Import process is starting.'),
      'progress_message' => t('Processed @current out of @total. Estimated time: @estimate.'),
      'error_message' => t('The process has encountered an error.'),
    ];

    foreach(array_slice($users_data,1) as $item) {
        $batch['operations'][] = [['\Drupal\batchimport\Form\BatchForm', 'update_content_type'], [$item]];
    }

    batch_set($batch);
    \Drupal::messenger()->addMessage('Imported ' . $total . ' Nodes!');
    @fclose($fp);
    $form_state->setRebuild(TRUE);
  }

  /**
   * @param $entity
   * Deletes an entity
   */
  public static function update_content_type($item, &$context) {
    $node = Node::create([
      'type' => 'sales',
      'title' => $item[1], 
      'body' => $item[2],
    ]);
    $saved = $node->save();
    if ($saved) {
      $context['results'][] = $item[0];
      $context['message'] = t('Updated node with ID @nid', array('@nid' => $nid));
    }
     else {
      $context['message'] = t('Failed to load node with ID @nid', array('@nid' => $nid));
     }
    }
}
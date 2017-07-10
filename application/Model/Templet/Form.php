<?
namespace Model\Templet;

class Form extends \Library{

	function generateForm( $data ){

		$form = new \Library\Form('list');
		$form->addElem('form', '', array(
			'' => ''
		));

		$form->addElem('file', 'favicon', array(
			'label' => _tr('Favicon')
		));

		if( is_dir(\Model\FileManager\Controller::UPLOAD_DIR) ){

			$files = $this->scanfile( \Model\FileManager\Controller::UPLOAD_DIR );
			$option = '<option></option>';
			foreach($files as $k => $v){

				$option .= '<option value="'. str_replace(_DIR, '', $v) .'">'. str_replace(_DIR, '', $v) .'</option>';
			}
		}

		if( !empty($data) ){

			foreach($data as $key => $value){

				$label = str_replace('.', '_', $key);
				$class = '';
				if( preg_match('/\#([a-zA-Z0-9_]+)$/i', $value) ){

					$label = '<span style="display:inline-block;width:50px;height:25px;background:'.$value.';"></span> ' . $key;
					$class = ' color-picker';
				}
				if( in_array(strtolower(pathinfo(basename($value), PATHINFO_EXTENSION)), array('png', 'jpg', 'jpeg', 'gif')) || in_array($value, array('background-image', 'background')) )
					$class = ' file-picker';

				$attr = array(
					'onclick' => 'this.setSelectionRange(0, this.value.length)',
					'class' => 'form-control' . $class,
					'label' => ( 
						in_array(strtolower(pathinfo(basename($value), PATHINFO_EXTENSION)), array('png', 'jpg', 'jpeg', 'gif')) 
						? 
						'<img src="'.trim($value).'" style="max-width:100px;max-height:100px;"/>' 
						: 
						$label
					),
					'name' => 'value['.$key.']',
					'value' => $value,
					'autocomplete' => 'off'
				);

				if( in_array(strtolower(pathinfo(basename($value), PATHINFO_EXTENSION)), array('png', 'jpg', 'jpeg', 'gif')) || in_array($value, array('background-image', 'background')) ){

					$form->addElem('text', $value, $attr)->after(
						'<div class="filepicker-div"><select class="form-control" name="filepicker">'.$option.'</select></div>'
					);
				}
				else{

					$form->addElem('text', $value, $attr);
				}
			}
		}

		$form->addElem('submit', 'update', array(
			'value' => _tr( 'Update' )
		));

		//$form->setData($data);
		$form->toString();
	}

	function advancedForm( $rows ){

		$form = new \Library\Form('list');
		$form->addElem('form', '', array(
			'' => ''
		));

		$form->addElem('text', 'new_file', array(
			'style' => 'display:inline-block;width:80%;',
			'placeholder' => _tr( 'Add file name or name with full path' )
		));

		$form->addElem('submit', 'addFile', array(
			'value' => _tr( 'Add file' ),
			'style' => 'margin-left:20px;',
		))->after('new_file');

		foreach($rows as $row){

			//for filename
			$form->addElem('text', 'files[]', array(
				'style' => 'display:inline-block;width:80%;',
				'value' => $row['path'] . '/' . $row['file'],
				'onclick' => 'this.setSelectionRange(0, this.value.length)'
			));
			$form->addElem('hidden', 'file_name[]', array(
				'value' => $row['path'] . '/' . $row['file'],
			))->after('files[]');

			$form->addElem('a', 'deleteFile', array(
				'href' => $this->url( array('action' => 'deleteFile') ) . '?file='.urlencode(str_replace('/Template/public/', '', $row['path']).'/'.$row['file']),
				'value' => _tr( 'Delete file' ),
				'style' => 'margin-left:20px;background-color:#ff0000;border-color:#ff0000;',
				'class' => 'btn btn-primary deleteFile'
			))->after('files[]');

			$form->addElem('textarea', 'content[]', array(
				'value' => htmlspecialchars($row['content']),
				'style' => 'width:1000px;height:500px;'
			));
		}

		$form->addElem('submit', 'updateFiles', array(
			'value' => _tr( 'Update' )
		));

		$form->errorLabel( $this->getError() );

		$form->setData($data);
		$form->toString();
	}

	public function styleForm( $designs, $preStyle = false ){

		//Zip FORM
		$form = new \Library\Form('list');
		$form->addElem('form', '', array(
			'' => ''
		));

		$form->addElem('span', 'span_label', array(
			'value' => _tr( 'Upload webdesign zip file, content must be in index.html file' )
		));

		$form->addElem('file', 'design_zip', array(
			'' => ''
		));

		$form->addElem('submit', 'uploadZip', array(
			'value' => _tr( 'Upload' )
		));

		$form->errorLabel( $this->getError() );

		$attr['tbody']['tr'][0]['td'] = array('style' => 'background:#eceeef;font-weight:bold;');
		$form->toString( $attr );

		
		//STYLE FORM
		$form = new \Library\Form('list');
		$form->addElem('form', '', array(
			'' => ''
		));

		$content = '';
		foreach( $designs as $row ){

			$content .= $row['content'];
		}

		$form->addElem('text', 'from_url', array(
			'placeholder' => _tr( 'Website url for downloading css, js and image files' ),
		));

		$form->addElem('textarea', 'content', array(
			'value' => $content,
			'style' => 'width:1000px;height:1000px;'
		));

		$form->addElem('submit', 'updateDesign', array(
			'value' => _tr( 'Update' )
		));

		$form->addElem('submit', 'reUpdateDesign', array(
			'value' => _tr( 'Update from source' ),
			'style' => 'margin-left:10px;'
		))->after('updateDesign');

		if( is_dir(_DIR . '/tmp/theme/origin') ){

			$form->addElem('submit', 'restoreDesign', array(
				'value' => _tr( 'Restore original' ),
				'style' => 'margin-left:10px;'
			))->after('updateDesign');
		}

		if( $preStyle ){

			$rows = \Table\content::singleton()->Select();
			if( !empty($rows) && isset($rows[1]) && $rows[1]['link_name'] != 'UNDER CONSTRUCTION' ){

				$form->addElem('submit', 'publishDesign', array(
					'value' => _tr( 'Publish' ),
					'style' => 'margin-left:40px;'
				))->after('updateDesign');
			}
			else{

				$form->addElem('submit', 'publishDesign', array(
					'value' => _tr( 'Publish' ),
					'style' => 'margin-left:40px;',
					'disabled' => 'disabled'
				))->after('updateDesign');
			}

			$form->addElem('a', 'previewDesign', array(
				'href' => _URI . '/?present=true',
				'target' => '_blank',
				'value' => _tr( 'Preview' ),
				'style' => 'margin-left:10px;',
				'class' => 'btn btn-primary'
			))->after('updateDesign');
		}

		if( \Session::designErrors() ){

			$errors = \Session::designErrors(true);
			$errorList = '';
			foreach($errors as $err){

				$errorList .= $err['get_from'] . '&nbsp;&nbsp;&nbsp;<br><a href="'.$this->url( array('action' => 'downloadManually') ) .'?file='. base64_Encode($err['get_from']). '&file_to=' .base64_encode($err['new']).'">Download manually</a><br><br>';
			}
			$form->errorLabel( 'Cannot download:<br>' . '<span style="font-size:12px;">' . $errorList . '</span>' );
		}

		$form->toString();
	}

	public function hiddenStyleForm( $data ){

		$form = new \Library\Form('list');

		foreach( $data as $row ){

			$form->addElem('span', $row['file'], array(
				'value' => $row['file'],
				'style' => 'font-weight:bold;'
			))->after('&nbsp;&nbsp;<input value="'.htmlspecialchars('<? require __DIR__ .\'/inc/'.$row['file'].'\'; ?>').'" class="form-control" style="display:inline-block;width:80%;" onclick="this.setSelectionRange(0, this.value.length)">');

			$form->addElem('textarea', 'some', array(
				'value' => htmlspecialchars($row['content']),
				'style' => 'width:1000px;height:500px;'
			));
		}

		$form->toString();
	}

	public function restoreForm(){

		$form = new \Library\Form('row');

		$form->addElem('data', 'name', array(
			'label' => _tr('Directory')
		));

		$form->addElem('a', 'restore', array(
			'href' => $this->url( array('action' => 'restoreFromBackup', 'directory' => '{directory}') ),
			'value' => _tr( 'Restore' ),
			'class' => 'btn btn-primary'
		));

		return $form;
	}
}
?>
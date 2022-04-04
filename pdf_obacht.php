<?php
// no direct access
defined( '_JEXEC' ) or die;

class plgContentPdf_Obacht extends JPlugin
{
	/**
	 * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;
	protected $app;
	protected $base_path;


	public function onContentAfterDisplay($context, &$article, &$params, $page = 0)
	{
		if($this->app->input->getCmd('view', '') == 'article')
		{	
			if(in_array($article->catid,$this->params->get("pdf_categories")))
			{
				if(strlen($article->jcfields[3]->value) == 7)
				{
					$pdf=explode("_",$article->jcfields[3]->value);
					
					$pfad=JURI::base()."/images/". $this->params->get("pdf_base_folder")."/".$pdf[1]."/".$pdf[0]."_".$pdf[1].".pdf";
					
					echo $this->base_path;
					$content='<div class="solid-container vereinszeitung"></div>';

					$content.='<script src="plugins/content/pdf_obacht/js/jquery.min.js"></script>';
					$content.='<script src="plugins/content/pdf_obacht/js/html2canvas.min.js"></script>';
					$content.='<script src="plugins/content/pdf_obacht/js/three.min.js"></script>';
					$content.='<script src="plugins/content/pdf_obacht/js/pdf.min.js"></script>';
					$content.='<script type="text/javascript">window.PDFJS_LOCALE = {pdfJsWorker: "'.JURI::base().'plugins/content/pdf_obacht/js/pdf.worker.js",pdfJsCMapUrl: "cmaps"};</script>';
					$content.='<script src="plugins/content/pdf_obacht/js/3dflipbook.min.js"></script>';
					
					$content.="<script type='text/javascript'>var options = { pdf: '".$pfad."',template: { html: '".JURI::base()."plugins/content/pdf_obacht/templates/default-book-view.html',  styles: ['".JURI::base()."plugins/content/pdf_obacht/css/white-book-view.css']}};
					
					jQuery('.vereinszeitung').FlipBook(options);
					</script>";
					
					return $content;
				}
			}
		}
		return "";
	}




	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	 public function onBeforeCompileHead()
	 {
		/*
		 * Plugin code goes here.
		 * You can access database and application objects and parameters via $this->db,
		 * $this->app and $this->params respectively
		 */
       
			if($this->app->isClient('site'))
			{
				$doc=$this->app->getDocument();
				$this->base_path=$this->params->get("pdf_base_folder");

				if($this->app->input->getCmd('view', '') == 'article')
				{	
				
					$id=$this->app->input->getInt('id');
					$article = JTable::getInstance("content");
					$article->load($id);

					if(in_array($article->catid,$this->params->get("pdf_categories")))
					{
						$doc->addHeadLink('plugins/content/pdf_obacht/css/style.css','stylesheet');
					}
				}
			}

		return true;
		}
}
?>
<?php
	require_once('Document.php');
	class Lien
	{
		/*public*/ function /*__construct*/Lien($filename = "", $texte = "", $target = "", $nbdaysold = 0)
		{
			global $tpl;
			global $home;
			$this->templ = $tpl;
			$this->home = $home;
			$this->document = null;
			if ($filename != "") $this->document = new Document($filename, $nbdaysold);
			$this->texte = $texte;
			$this->target = $target;
			$this->lien = "";
			if ($filename != "") $this->lien = $this->document->getFileName();
			$debug = 0;
			if ($debug)
				echo "<P>texte=".$this->texte.", target=".$this->target.", lien=".$this->lien."</P>\n";
		}

		/*public*/ function addLienDocument($filename, $texte = "", $target = "", $nbdaysold = 0)
		{
			$this->document = new Document($filename, $nbdaysold);
			$this->lien = $this->document->getFileName();
			if ($texte != "") $this->texte = $texte;
			if ($target != "") $this->target = $target;
		}

		/*public*/ function addLienHTML($lien, $texte = "", $target = "")
		{
			$this->lien = $lien;
			if ($texte != "") $this->texte = $texte;
			if ($target != "") $this->target = $target;
		}

		/*public*/ function getTarget()
		{
			return $this->target ? 'target="' . $this->target . '"' : "";
		}

		/*public*/ function GetHTMLCode()
		{
			$res = "";
			if ($this->document->nb_days_old > 0) {
				if ($this->document != null) $res = $this->document->getHTMLImageNewIfOldier();
			}
			$res .= '<a ' . $this->getTarget() . ' href="' . $this->lien . '">' . $this->texte . '</a>';
			return $res;
		}

	}
?>

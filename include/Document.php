<?php
	require_once('TemplateEngine.php');
	class Document
	{
		/*public*/ function /*__construct*/Document($filename, $nbdaysold = 0)
		{
			global $tpl;
			global $home;
			$this->templ = $tpl;
			$this->home = $home;
			$this->file_name = addslashes($filename);
			$this->nb_days_old = $nbdaysold;
			$this->dir_name = "";
			$this->file_type = "Unknown";
			$this->file_icone = "";
			$this->file_ext = "Empty";
		}

		/*private*/ function getFileName()
		{
			return $this->file_name;
		}

		/*public*/ function fileExists()
		{
			return file_exists($this->file_name);
		}

		/*private*/ function getFileType()
		{
			$ext = strrchr($this->file_name, ".");
			if ($ext)
			{
				$this->file_ext = substr($ext, 2);
				if ($ext == ".doc")
				{
					$this->file_type = "Word";
					$this->file_icone = "icone-word.gif";
				}
				elseif ($ext == ".pdf")
				{
					$this->file_type = "PDF";
					$this->file_icone = "icone-pdf.gif";
				}
				elseif ($ext == ".xls")
				{
					$this->file_type = "Excel";
					$this->file_icone = "icone-xls.gif";
				}
				elseif ($ext == ".jpg")
				{
					$this->file_type = "JPEG";
					$this->file_icone = "icone-jpg.gif";
				}
				elseif ($ext == ".gif")
				{
					$this->file_type = "GIF";
					$this->file_icone = "icone-gif.gif";
				}
				else
				{
					$this->file_type ="Unknown";
					$this->file_icone = "icone-file.gif";
				}
			}
			return $this->file_type;
		}

		/*public*/ function getDateOfFile()
		{
			if ($this->fileExists())
			{
				$dat = date("Ydm", filemtime($this->file_name));
			} else {
				$dat = "";
			}
			return $dat;
		}

		/*public*/ function isOldierThanInDays($days)
		{
			$thereIsNDays = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
			if ($this->fileExists())
			{
				//echo "aujourdh'ui: ".mktime().", hier: ".mktime(0,0,0,date("m"),date("d")-1).", il y a ".$days." jours: ".$thereIsNDays.", date du fichier: ".filemtime($this->file_name)."<br />";
				if (filemtime($this->file_name) < $thereIsNDays) {
					$res = true;
				} else {
					$res = false;
				}
			} else {
				$res = false;
			}
			//echo "isOldierThanInDays=".$res."<br />";
			return $res;
		}

		/*public*/ function getHTMLImageNew()
		{
/*
<img src="${imgfile}" alt="${imgtext}" ${imgattr} />
*/
			global $imgfile; $imgfile = $this->home . "img/new3.gif";
			global $imgtext; $imgtext = "New";
			global $imgattr; $imgattr = "";
			return $this->templ->getHTMLCode("ImageNew");
		}

		/*public*/ function getHTMLImageNewIfOldier()
		{
			$res = "";
			if (! $this->isOldierThanInDays($this->nb_days_old))
			{
				$res = $this->getHTMLImageNew();
			}
			return $res;
		}

		/*public*/ function GetHTMLCode($txt)
		{
/*
${imgnew}<a href="${fname}">
<img border="0" height="25" alt="Fichier ${ftype}" src="${ficone}" /> (${fsize} Ko)
${texte}</a>
*/
			global $fname; $fname = $this->file_name;
			global $imgnew; $imgnew = $this->getHTMLImageNewIfOldier();
			global $ftype; $ftype = $this->getFileType();
			global $ficone; $ficone = $this->home . "img/" . $this->file_icone;
			global $fsize; $fsize = round(filesize($fname) / 1024);
			global $texte; $texte = $txt;
			//echo "fname=$fname<br />ftype=$ftype, ficone=$ficone, fsize=$fsize<br />\n";
			return $this->templ->GetHTMLCode("UnFichier");
		}

	}

?>

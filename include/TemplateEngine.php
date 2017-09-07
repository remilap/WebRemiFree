<?php
	class TemplateEngine
	{
		/*private*/var $originalSubTemplates;
		/*private*/var $modifiedSubTemplates;
		/*public*/ function /*__construct*/TemplateEngine($filename)
		{
			$this->filename = addslashes($filename);
			$this->originalSubTemplates = '';
			$this->modifiedSubTemplates = '';
			$this->parseFile();
		}

		/*private*/ function parseFile()
		{
			if (file_exists($this->filename))
			{
				$content = file_get_contents($this->filename);
				preg_match_all("#<!---===== begin_template (\w*) =====--->(.*)<!---===== end_template =====--->#sU",
						$content, $matchedExp, PREG_PATTERN_ORDER);

				$subTemplatesNames = $matchedExp[1];
				$subTemplatesContent = $matchedExp[2];

				foreach ($subTemplatesNames as $index => $subTemplateName)
				{
					$this->originalSubTemplates[$subTemplateName] = $subTemplatesContent[$index];
				}
			}
			else /*throw new Exception*/echo('Le fichier '.$this->filename.' n\'existe pas.');
		}

		/*private*/ function setVariables($templateName)
		{
			# Recuperation de toutes les variables du template.
			preg_match_all('#\$\{(.*)\}#U', $this->originalSubTemplates[$templateName], $varNames, PREG_PATTERN_ORDER);
			$varNames = $varNames[1];

			# Reinitialisation du template modifie, au cas ou des traitements aient deja eu lieu sur celui-ci.	
			$this->modifiedSubTemplates[$templateName] = $this->originalSubTemplates[$templateName];

			# Pour chaque variable, on remplace celle-ci par sa valeur.
			foreach ($varNames as $varName)
			{
				global $$varName;
				//if ($templateName == "UnFichier") echo "varName=$varName, value=". $$varName ."<br />\n";
				$this->modifiedSubTemplates[$templateName] = preg_replace('#\$\{'.$varName.'\}#', $$varName, $this->modifiedSubTemplates[$templateName]);
			}
		}

		/*public*/ function GetHTMLCode($templateName)
		{
			$this->setVariables($templateName);
			return $this->modifiedSubTemplates[$templateName];
		}

		/*public static*/ function Output($buffer)
		{
			echo $buffer;
		}
	}

?>

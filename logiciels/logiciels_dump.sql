-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Serveur: remi.lapointe.sql.free.fr
-- Généré le : Mardi 22 Mai 2007 à 00:16
-- Version du serveur: 5.0.37
-- Version de PHP: 4.4.4
-- 
-- Base de données: `remi_lapointe`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `logiciels_liste`
-- 

CREATE TABLE `logiciels_liste` (
  `Index` bigint(20) NOT NULL auto_increment,
  `Nom` varchar(20) collate latin1_general_ci NOT NULL,
  `Version` varchar(20) collate latin1_general_ci NOT NULL,
  `Genre` set('Bureautique','Navigateur','Système','Registre','Dessin','Photo','Video','Musique','Culturel','Educatif','Communication','Bureau Windows','Antivirus','Programmation') collate latin1_general_ci NOT NULL,
  `Contenu` varchar(200) collate latin1_general_ci NOT NULL,
  `Provenance` varchar(50) collate latin1_general_ci NOT NULL,
  `Date` date NOT NULL,
  `Site Web` varchar(100) collate latin1_general_ci NOT NULL,
  `Telechargement` varchar(200) collate latin1_general_ci NOT NULL,
  `Sur mon site` varchar(120) collate latin1_general_ci NOT NULL,
  `Installé` set('non','PC fixe','PC portable','PC Alcatel') collate latin1_general_ci NOT NULL default 'non',
  `Evaluation` enum('non','super','très bien','bien','bof','à ne pas installer') collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`Index`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=63 ;

-- 
-- Contenu de la table `logiciels_liste`
-- 

INSERT INTO `logiciels_liste` VALUES (47, 'activePDF Portfolio', '', 'Bureautique', 'Contains Server, DocConverter, WebGrabber, Toolkit, and Printer', 'activePDF', '2006-02-13', 'http://www.activepdf.com/;activepdf.com', 'http://www.activepdf.com/downloads/serverproducts/index.cfm;prtfolio.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (50, 'Ad-Aware SE Personal', 'v1.06', 'Antivirus', 'Rid your system of adware and spyware', 'PCWORLD', '2006-03-14', 'http://www.pcworld.com/;pcworld.com', 'http://www.pcworld.com/downloads/file_description/0,fid,7423,00.asp;aawsepersonal.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (45, 'Aide mémoire', 'v2.3', 'Bureau Windows', 'Gestion de post-it', '', '2006-01-23', 'http://www.lsi-dev.com/;lsi-dev.com', 'http://www.lsi-dev.com/index.php?mod=archives&ac=voir&id=2;Aide_memoire.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (25, 'AirCrack', '', 'Communication', 'Piratez votre propre clé WEP', 'PC EXPERT Octobre 2005', '2005-10-04', 'http://100h.org/wlan/aircrack/;100h.org', 'aircrack-2.4.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (26, 'AirCrack', '', 'Communication', 'Mode d''emploi', 'PC EXPERT Octobre 2005', '2005-10-04', 'http://www.cr0.net:8040/code/network/aircrack/#q080;cr0.net', 'peek.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (46, 'Bi-Exploreur', '', 'Système', 'Un explorateur Windows avec 2 panneaux de navigation', '', '2006-01-23', 'http://www.lsi-dev.com/;lsi-dev.com', 'http://www.lsi-dev.com/index.php?mod=archives&ac=voir&id=12;Bi-Explorer.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (38, 'Calendrier 2006', '', 'Bureau Windows', 'Calendrier interactif Voyages-SNCF', 'Site voyages-sncf.com', '2006-01-16', 'http://calendrier.voyages-sncf.com/;voyages-sncf.com', 'http://calendrier.voyages-sncf.com/popups/Calendrier_2006.zip;Calendrier_2006.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (35, 'Celestia', '1.4.0', 'Culturel', 'Cyber-voyage dans le cosmos', '20 minutes 16/01/2006', '2006-01-16', 'http://www.shatters.net/celestia;shatters.net', 'http://www.shatters.net/celestia/download.html;celestia-win32-1.4.0.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (36, 'Celestia', '1.4.0', 'Culturel', 'interface en Français', '20 minutes 16/01/2006', '2006-01-16', 'http://www.ikiru.ch/celestia/forum/index.php?showtopic=39;ikiru.ch', 'http://www.ikiru.ch/celestia/forum/index.php?act=Attach&type=post&id=57;Celestia_1_4_0pre7_FR.zip', '', 'PC fixe', 'super');
INSERT INTO `logiciels_liste` VALUES (37, 'Celestia', '1.4.0', 'Culturel', 'catalogue de plug-in', '20 minutes 16/01/2006', '2006-01-16', 'http://www.celestiamotherlode.net/;celestiamotherlode.net', '', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (16, 'Cobian Backup', '7', 'Système', 'mettre les données à l''abri', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.cobian.se/;cobian.se', 'http://www.educ.umu.se/%7Ecobian/programz/Cb7Setup.exe;Cb7Setup.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (27, 'CommView for Wifi', '', 'Communication', '', 'PC EXPERT Octobre 2005', '2005-10-04', 'http://www.tamos.com/;tamos.com', 'http://www.tamos.com/download/main/ca.php;ca5.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (22, 'Copernic Desktop Sea', '1.2', 'Bureau Windows', 'sans lui, vous êtes perdu', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.copernic.com/;copernic.com', 'http://download3.copernic.com/copernicdesktopsearchfr.exe;copernicdesktopsearchfr.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (5, 'Daemon Tools', '3.47', 'Système', 'plus besoin du disque !', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.daemon-tools.cc/;daemon-tools', 'http://www.daemon-tools.cc/dtcc/portal/download.php?mode=Download&id=34;daemon347.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (2, 'Desktop Sidebar', '', 'Bureau Windows', 'un avant-goût de Longhorn', 'Windows News No Spécial été 2005', '0000-00-00', 'http://www.desktopsidebar.com/;desktopsidebar.com', 'http://www.desktopsidebar.com/files/sidebarb75.exe;sidebarb75.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (11, 'Diskeeper Lite', '7', 'Système', 'pour que le disque dure', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.diskeeper.com/;diskeeper.com', 'http://www.diskeeper.com/downloads/downloads-r.asp;downloads-r.asp', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (17, 'Double Driver', '', 'Système', 'maîtriser le pilotage', 'Windows News No Spécial été 2005', '2005-07-06', 'http://boozet.xepher.net/;boozet.xepher.net', 'http://boozet.xepher.net/download/dd100.exe;dd100.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (31, 'DualXplorer', '0.842', 'Bureau Windows', 'Gestionnaire de fichiers compact et astucieux', 'PC EXPERT Novembre 2005', '2005-11-07', 'http://www.technojoly.net/;technojoly.net', 'http://www.technojoly.net/request.php?2;DualXplorer0842.zip', '', 'PC fixe', 'très bien');
INSERT INTO `logiciels_liste` VALUES (29, 'DVD Shrink', '3.2', 'Système', 'Copie de DVD', 'SVM Mars 2004', '2004-03-16', 'http://www.dvdshrink.org/;dvdshrink.org', 'http://www.mrbass.org/dvdshrink/dvdshrink32setup.zip;dvdshrink32setup.zip', '', 'PC fixe', 'bien');
INSERT INTO `logiciels_liste` VALUES (6, 'EasyCleaner', '2', 'Système', 'le grand coup de balai', 'Windows News No Spécial été 2005', '2005-07-06', 'http://personal.inet.fi/business/toniarts;personal.inet.fi', 'http://personal.inet.fi/business/toniarts/files/EClea2_0.zip;EClea2_0.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (12, 'Everest Home Edition', '1.51', 'Système', 'un sommet du diagnotic', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.lavalys.com/;lavalys.com', 'http://www.lavalys.com/downloads/everesthome220.exe;everesthome220.exe', '', 'PC fixe', 'bien');
INSERT INTO `logiciels_liste` VALUES (43, 'Google Desktop', '', 'Bureau Windows', '', 'Google', '2006-01-23', 'http://desktop.google.fr/;google.fr', 'http://desktop.google.fr/;desktop.google.fr', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (44, 'Google Earth', '', 'Culturel', '', 'Google', '2006-01-23', 'http://earth.google.com/;google.com', 'http://dl.google.com/earth/GoogleEarth.exe;GoogleEarth.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (42, 'Google Toolbar', '', 'Navigateur', 'Barre d''outils Google pour Firefox', 'Google', '2006-01-23', 'http://toolbar.google.fr/;google.fr', 'http://toolbar.google.com/firefox/T3/intl/fr/index.html;Google Toolbar', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (24, 'Groove Virtual Offic', '', 'Bureau Windows', 'Votre bureau sur Internet', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.groove.net/;groove.net', 'http://www.groove.net/downloads/groove/download-preview.cfm;download-preview.cfm', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (39, 'J2SE', '1.4.2', 'Programmation', 'Java 2 Platform, Standard Edition', 'Java', '2006-01-23', 'http://java.sun.com/j2se/;java.sun.com', 'http://java.sun.com/j2se/1.4.2/download.html;j2sdk-1_4_2_10-windows-i586-p.exe', '', 'PC portable', '');
INSERT INTO `logiciels_liste` VALUES (28, 'Konfabulator', '2.1.1', 'Bureau Windows', 'Des Widgets à gogo', 'PC EXPERT Octobre 2005', '2005-10-04', 'http://www.konfabulator.com/;konfabulator.com', 'http://www.konfabulator.com/Konfabulator_2.1.1.exe;Konfabulator_2.1.1.exe', '', 'PC fixe', 'très bien');
INSERT INTO `logiciels_liste` VALUES (15, 'Nero Info Tools', '', 'Système', 'quel disque suis-je ?', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.nero.com/;nero.com', 'ftp://ftp6.nero.com/InfoTool.zip;InfoTool.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (40, 'NetBeans IDE', '4.1', 'Programmation', 'Fully-featured Java IDE', 'Java', '2006-01-23', 'http://www.netbeans.org/;netbeans.org', 'http://www.netbeans.info/downloads/download.php; netbeans-4_1-windows.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (14, 'nLite', '0.99.8 beta 5', 'Système', 'le jour viendra où il faudra réinstaller Windows', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.nliteos.com/;nliteos.com', 'http://nuhi.olmik.net/nlite1.0b6.exe;nlite1.0b6.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (19, 'PC Inspector File Re', '3.0', 'Système', 'fouilleur de corbeille !', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.convar.de/;convar.de', 'http://download.pcinspector.de/pci_filerecovery.exe;pci_filerecovery.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (41, 'Picasa', '', 'Photo', 'Picasa recherche et organise instantanément toutes les photos stockées sur votre ordinateur', 'Google', '2006-01-23', 'http://picasa.google.fr/;google.fr', 'http://dl.google.com/picasa/picasa2-current.exe;picasa2-current.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (10, 'Power Toys for Windo', '', 'Bureau Windows', 'douze outils en plus', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.microsoft.com/windowsxp;microsoft.com', 'http://www.microsoft.com/windowsxp/downloads/powertoys/xppowertoys.mspx;xppowertoys.mspx', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (48, 'QuickTime', '', 'Video', 'Lecteurs vidéo et DVD', '01net.com', '2006-02-13', 'http://www.01net.com/;01net.com', 'http://www.01net.com/telecharger/windows/Multimedia/lecteurs_video_dvd/fiches/100.html;QuickTimeInstaller.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (21, 'RamBoost XP', '', 'Système', 'pour une mémoire encore plus vive', 'Windows News No Spécial été 2005', '2005-07-06', 'http://magic56.free.fr/;magic56.free.fr', 'http://magic56.free.fr/rambxpfr.zip;rambxpfr.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (32, 'RamBoost XP', '4.0.6.324', 'Système', 'Optimiser la mémoire vive d''un PC', 'PC EXPERT Novembre 2005', '2005-11-07', 'http://magic56.free.fr/;magic56.free.fr', 'http://magic56.free.fr/rambxpfr.zip;rambxpfr.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (20, 'RegCompact', '', 'Registre', 'remise en forme du registre', 'Windows News No Spécial été 2005', '2005-07-06', 'http://perso.wanadoo.fr/websecurite/;wanadoo.fr/websecurite', 'http://perso.wanadoo.fr/websecurite/RegCompact1.0.exe;RegCompact1.0.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (23, 'Service Controler XP', '2.2', 'Système', 'ordonnez les services', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.deepeshagarwal.tk/;deepeshagarwal.tk', 'http://files5.majorgeeks.com/files/bb5b42e968d61f4ae1d97ab633b3a614/admin/setup_scxpv2.2.61.zip;setup_scxpv2.2.61.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (49, 'Spybot', '1.3', 'Antivirus', 'Spybot Search and Destroy', 'PCWORLD', '2006-03-14', 'http://www.pcworld.com/;pcworld.com', 'http://www.pcworld.com/downloads/file_description/0,fid,22262,00.asp;spybotsd13.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (4, 'SuperCopier', '1.35', 'Système', 'mieux copier, plus vite', 'Windows News No Spécial été 2005', '2005-07-06', 'http://sfxteam.fr.st/;sfxteam.fr.st', '', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (18, 'SyncBack', '3.2', 'Système', 'sauvegarder sans soucis', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.2brightsparks.com/;2brightsparks.com', 'http://www.2brightsparks.com/assets/software/SyncBack_Setup_FR.zip;SyncBack_Setup_FR.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (7, 'TaskArrange', '1.1', 'Bureau Windows', 'chaque tâche à sa place', 'Windows News No Spécial été 2005', '2005-07-06', 'http://users.forthnet.gr/pat/efotinis;users.forthnet.gr', 'http://users.forthnet.gr/pat/efotinis/programs/files/TaskArrange1.1.1.zip;TaskArrange1.1.1.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (51, 'TCPOptimizer', '', 'Système,Communication', '', 'infos-du-net', '2006-03-14', 'http://www.infos-du-net.com/;infos-du-net.com', 'http://www.infos-du-net.com/telecharger/tele457.html;TCPOptimizer.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (13, 'Ultimate Boot CD', '3.3', 'Système', 'Système Linux en cas de panne Windows', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.ultimatebootcd.com/;ultimatebootcd.com', 'http://www.fulminati.org/ubcd33-full.zip;ubcd33-full.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (8, 'X-Setup', '6.3', 'Système', '1600 réglages d''un clic', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.xsetup.net/;xsetup.net', 'http://www.xsetup.net/?xset=xset63&type=pf&file=pf6316.exe&ctrl=2ca8d796910daceb19b632f6c2ce9757;pf6316.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (9, 'XP AntiSpy', 'V3.93', 'Antivirus', 'détecteur d''espions', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.xp-antispy.rog/;xp-antispy.rog', '', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (3, 'Xplorer2 Lite', '1.1', 'Bureau Windows', 'cet explorateur va plus loin', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.zabkat.com/;zabkat.com', 'http://www.zabkat.com/x2lite.htm;x2lite.htm', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (1, 'Zeb-Utility', '1.0', 'Registre', 'Optimiser la Base de registres', 'Windows News No Spécial été 2005', '2005-07-06', 'http://www.zebulon.fr/;zebulon.fr', 'Setup_Zeb-Utility.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (30, 'ZGuideTV', 'beta 7', 'Video', 'Guide électronique de programmes télévisuels', '20 minutes 17/10/2005', '2005-10-17', 'http://sourceforge.net/projects/zguidetv;sourceforge.net', 'http://prdownloads.sourceforge.net/zguidetv/ZGuideTV_beta_7_comp098.zip?download;ZGuideTV_beta_7_comp098.zip', '', 'PC fixe', 'bof');
INSERT INTO `logiciels_liste` VALUES (34, 'ZNsoft Icon Maker', '2.0', 'Dessin', 'Editeur d''icones et de curseurs', 'ZNsoft', '2005-11-07', 'http://znsoft.free.fr/tele.htm;znsoft.free.fr', 'http://znsoft.free.fr/ZNsoftIcon.zip;ZNsoftIcon.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (33, 'ZNsoft Optimizer XP', '2.1', 'Système', 'Optimiser un PC XP', 'PC EXPERT Novembre 2005', '2005-11-07', 'http://znsoft.free.fr/tele.htm;znsoft.free.fr', 'http://znsoft.free.fr/ZNsoftXp.zip;ZNsoftXp.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (52, 'Mozilla firefox', '1.5.0', 'Navigateur', '', '', '2006-06-12', 'http://www.mozilla-europe.org/fr/products/firefox/;mozilla-europe.org', 'http://download.mozilla.org/?lang=fr&product=firefox-1.5.0.1&os=win;Firefox Setup 1.5.0.1.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (53, 'FileZilla', '2.2.18', 'Communication', 'Transfert de fichiers par ftp', '', '2006-06-12', 'http://filezilla.sourceforge.net/;sourceforge.net', 'http://prdownloads.sourceforge.net/filezilla/FileZilla_2_2_18_setup.exe?download;FileZilla_2_2_18_setup.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (54, 'PageDefrag', 'v2.2', 'Système', 'Defragmentation des fichiers systèmes lors du boot de Windows', '', '2006-05-16', 'http://www.sysinternals.com/;sysinternals.com', 'http://www.sysinternals.com/Files/PageDefrag.zip;PageDefrag.zip', '', 'PC fixe,PC portable', 'très bien');
INSERT INTO `logiciels_liste` VALUES (55, 'cyber30', '', 'Communication', 'Amélioration des paramètres de communication IP', '', '2006-05-16', 'http://www.networkingfiles.com/PingFinger/CyberKit.htm;networkingfiles.com', 'http://www.networkingfiles.com/PingFinger/downloads/cyberkitdownload.htm;cyber30.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (56, 'ipscan12', '', 'Communication', 'Outils d''analyse de la communication IP', '', '2006-05-16', 'http://www.networkingfiles.com/PingFinger/CyberKit.htm;networkingfiles.com', 'http://www.networkingfiles.com/PingFinger/downloads/advancedipscannerdownload.htm;ipscan12.zip', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (57, 'iTunesSetup', '', 'Musique', '', '', '2006-05-16', 'http://www.apple.com/fr/;apple.com', 'http://www.apple.com/fr/itunes/download/;iTunesSetup.exe', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (58, 'MSN Messenger', '', 'Communication', '', '', '2006-10-16', 'http://messenger.msn.fr/Xp/Default.aspx;messenger.msn.fr', 'http://messenger.msn.fr/xp/downloadDefault.aspx;Install_MSN_Messenger.EXE', '', 'PC fixe,PC portable', '');
INSERT INTO `logiciels_liste` VALUES (59, 'CONJUG', '', 'Educatif', 'Toutes les conjugaisons françaises', '', '2006-10-16', '', '', '', 'non', '');
INSERT INTO `logiciels_liste` VALUES (60, 'Outlook Express Quic', '', 'Bureautique', '', 'OI n°170', '2006-02-14', 'http://www.oehelp.com/OEBackup/;oehelp.com', 'http://www.oehelp.com/OEBackup/oeqbfull.zip;oeqbfull.zip', '', 'PC portable', '');
INSERT INTO `logiciels_liste` VALUES (61, 'Acrobat Reader', '', 'Bureautique', 'Adobe Acrobat Reader', '', '2006-02-14', 'http://www.adobe.fr/products/acrobat/;adobe.fr', 'http://ardownload.adobe.com/pub/adobe/reader/win/7x/7.0.7/fra/AdbeRdr707_DLM_fr_FR.exe;AdbeRdr707_DLM_fr_FR.exe', '', 'PC fixe,PC portable,PC Alcatel', '');
INSERT INTO `logiciels_liste` VALUES (62, 'ArcSoft PhotoStudio', '5.5', 'Dessin', '', 'ArcSoft', '2006-06-05', 'http://arcsoft.com/products/photostudio/;arcsoft.com', 'http://arcsoft.com/support/downloads/downloadnow.asp?downloadid=229;PhotoStudio_Fre.exe', '', 'non', '');

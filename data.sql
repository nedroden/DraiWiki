-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 16, 2017 at 05:33 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

--
-- Database: `DraiWiki`
--

--
-- Dumping data for table `drai_agreement`
--

INSERT INTO `drai_agreement` (`id`, `body`, `locale_id`) VALUES
  (1, 'This is the user agreement', 1);

--
-- Dumping data for table `drai_article`
--

INSERT INTO `drai_article` (`id`, `title`, `locale_id`, `status`) VALUES
  (1, 'Homepage', 1, 1),
  (2, 'About this cool article', 1, 2),
  (6, 'This is a new article', 1, 1),
  (7, 'The Netherlands', 1, 1),
  (8, 'Concorditer tantum si adhuc facundia', 1, 1),
  (9, 'Habebatur flammas gignis gratus undas sororem', 1, 1),
  (10, 'Tibi quibus et ipsaque adorat', 1, 1),
  (11, 'Prodis perdidimus vocatos demisere inopem nec ferat', 1, 1),
  (12, 'Headings', 1, 1);

--
-- Dumping data for table `drai_article_history`
--

INSERT INTO `drai_article_history` (`id`, `article_id`, `user_id`, `body`, `updated`) VALUES
  (1, 1, NULL, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n#### 2.1.1. Minimum\r\n* PHP 7.1+\r\n* MariaDB / MySQL\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n### 2.2. How to install\r\n1. Install Composer and NPM. If you\'re on a shared hosting and can\'t use the terminal, look at section 2.3\r\n2. cd to your http directory\r\n3. Run the following command in your command prompt or terminal: git clone https://github.com/Chistaen/DraiWiki.git\r\n4. Use Composer to install the required packages (composer install)\r\n5. Use NPM to install the required JS libraries (npm install)\r\n6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting\r\n7. Run the DDL (table creation) and DML (data insertion) .sql files: ddl.sql and dml.sql.\r\n8. Enjoy!\r\n\r\n### 2.3. Troubleshooting\r\n#### 2.3.1. Help! I don\'t have access to a terminal!\r\nIf you\'re on a shared hosting that doesn\'t allow you to install Composer/NPM, don\'t worry. There\'s another solution. Just download the files to your computer and install the Composer and NPM packages from your computer\'s terminal. Then re-upload the files to your hosting. Happy writing!\r\n\r\n## 3. Open positions\r\nWe\'re always looking to expand our team. Currently, the following positions are open:\r\n* Development\r\n* Quality Assurance\r\nGo to our forum to apply:\r\nhttps://draiwiki.robertmonden.com/forum', '2017-06-16 15:20:06'),
  (2, 2, NULL, 'This is the about page</div></div></div><strong><h1>', '2017-06-16 18:47:49'),
  (10, 2, 1, 'This is the about page</div></div></div><strong><h1>Test', '2017-07-01 18:48:22'),
  (11, 2, 1, 'Oh, wow, this is s cool.', '2017-07-01 18:48:34'),
  (12, 2, 1, 'Oh, wow, this is s cool.', '2017-07-01 18:49:16'),
  (13, 2, 1, 'Oh, wow, this is s cool.', '2017-07-01 18:49:23'),
  (14, 2, 1, 'Oh, wow, this is s cool.', '2017-07-01 18:50:14'),
  (15, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 18:52:26'),
  (16, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 18:55:02'),
  (17, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 18:56:10'),
  (18, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 18:56:16'),
  (19, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 18:57:15'),
  (20, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 18:58:06'),
  (21, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 18:58:31'),
  (22, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 19:01:02'),
  (23, 2, 1, 'Oh, wow, this is s cool.test', '2017-07-01 19:01:12'),
  (24, 2, 1, 'It finally works.', '2017-07-01 19:01:28'),
  (25, 2, 1, 'It finally works. Hurrah! </div></div></div><strong><div><h1>', '2017-07-01 19:01:43'),
  (26, 2, 1, 'It finally works. Hurrah! </div></div></div><strong><div><h1>', '2017-07-01 19:01:59'),
  (27, 2, 1, 'It finally works. Hurrah! </div></div></div><strong><div><h1>', '2017-07-01 19:05:57'),
  (28, 6, 1, 'So is it working?', '2017-07-01 19:06:38'),
  (29, 7, 1, 'The **Netherlands** is a country in Europe, located west of Germany and north of Belgium. Its capital is Amsterdam, which also happens to be the country\'s biggest city in terms of population.', '2017-07-02 18:19:29'),
  (30, 8, 1, '## Piscem soror mittunt comitique quam\r\n\r\nLorem markdownum iuvenis ferat arma, aura epulanda glaebis pondus titulum\r\npronus. Qui geris non latrantibus [patuit](http://undis.com/sed) ac post dixerat\r\nvidisse, reflectitur undas. Esse obscurus, strata, illis forent Chimaeriferae\r\nlicet? Infelix tu vires nactus, qua tenet ad classe parentem aequales nubibus\r\nParnasi bis templa tumentem in dixit.\r\n\r\n## Quisquam atque ibi tempore vero\r\n\r\nAris nec esse longo tot prodesse, brevem veteremque *facie*. Nudorum aequoreis\r\nrumpit Hectoris! Est populi regna in quoque novo fecisse tacitus convicia sive,\r\nille *quem* qui. Cum gens posuere hac: triformis [nocens ex\r\ncontinui](http://www.cum.com/indignave.aspx) Deucalion ferox; quo.\r\n\r\n## Huic partu illis Musa interea saxo tamen\r\n\r\nHis positae in haerebis vitiatis sed Cythereide crescitque esse; sic ait non,\r\nformae. Bracchia Cerealia, quoque umbrasque magnis dum nobis spumis.\r\n\r\n    printer.menu_dvd = syntax.wired_web_algorithm(\r\n            dmaBccSms.encryption_mebibyte_hfs.downloadOlePacket(-4,\r\n            errorTransfer));\r\n    wddmTebibyte += point_margin(-2);\r\n    publishing_enterprise(digital_computer + port_blob, component_dma +\r\n            bar_kibibyte_debug, ethicsHorizontal(xp_malware) * spoofingYoutube);\r\n    eideCad.bank_pitch_hardening(rup.ole(volume(hardening,\r\n            mountainIntegrated)));\r\n    var dlc = deviceVectorProm;\r\n\r\n## Si animam\r\n\r\nSententia rogantem magnasque mixtaeque, diu Calydona, habere utque. Regnat astra\r\nperennis observo et albet iuravimus arduus laborum nactus habet volat. Habent\r\nbellique servitura tento; duarum nobis ut **erant paene agmine** studioque erat,\r\nque iugum nisi tinguit coniuge.\r\n\r\n    drive_rom.partitionOutbox = 91;\r\n    win -= remote_drop;\r\n    itunes = ddr;\r\n    multitasking -= cyberspaceJumper.ergonomicsVideoFrozen.flowchart(vle,\r\n            adc_memory_bluetooth, lossy_www_web) + 1;\r\n    if (proxyModem + errorExecutable >= printer) {\r\n        threadingDuplexGibibyte += ditheringMenuHost + metafileClick +\r\n                widgetZebibyte;\r\n        ics(flash, lpi_plug + cifsScanIntegrated);\r\n        file_registry_dual += bcc(22, 3, menuDesignTunneling);\r\n    } else {\r\n        multiAdware(901292 + mebibyteLun, 1, timeLossy.ajax_mask(-4));\r\n    }\r\n\r\nMaesto tacito? Frondente tamen humum, et amari caeli inposita regia purasque.\r\nMelioribus pelle pavefactaque dicitur, capi Dictaeaque mira percussis. Versasse\r\nstupet nec defuit iaculum. Dictis dolor: vimine pars cernit Dianae dixit pacis\r\nmundi aequaverit [sequentes](http://animam.io/et-stridore) iusto marmor,\r\nprofugam, leto.\r\n', '2017-07-02 18:32:51'),
  (31, 9, 1, '## Ego ausis mentem iuveni iacentes\r\n\r\nLorem markdownum in tibi animi luna eum umenti cucurri at ille. Mutasse ebrius\r\n*minister nodosaque* coloni tamen et proxima, **senecta fugere** perituraque.\r\nNihil dabat pectora collectum hominemque adfuit, ostendit numeratur secus atria\r\ncum omnia se caelique volenti crimen vivacis.\r\n\r\n> Terga sub finxit ictus saxum sinuantur [ingeniis alta\r\n> monstra](http://www.qui-color.org/) umbra, ore munere, effectum adeat ut\r\n> Proreus! Servantis tuus porrecta *aevis*, illum manusque et altum corpore\r\n> pretioque adfectus invidiosa nemus abductas, gener ille Elei. Atlantis rubet\r\n> tibi epulis est conclamat vana, testes dum coniunx *ossibus*, nitidissimus.\r\n> Dubioque ulmo locus prohibes frondes, Pirenida Ionio arcus ira. Laterum\r\n> defendere dabat fulmina!\r\n\r\nEt primusque merito. Laeta parte rugosoque vacuis aulaea currus relicta\r\n[regesta](http://tenebrisquevellet.net/), solebat senatus nomen.\r\n\r\n## Insequitur questi\r\n\r\nEst modo rigore! Novat fuit racemis Vestaque Acrisioniades parva undis timor\r\nStygios. Vox rogando ore tamen puro Midan, dolor caeli Icare? Vera iudice: et\r\nmons tamen terrasque tamen cernit Ulixes? Ope vides *male*; arma caput idemque:\r\ntremuloque hospes vivacemque **cultu**.\r\n\r\n1. Est quo nimios dixit diduxit sint\r\n2. Quatiens Cyane temptat illa mixta conantesque animas\r\n3. Iampridem siquid cava eodem ultima Antiphataeque saxo\r\n\r\n## Lycisce est gravidamve\r\n\r\nPosuistis animosa silva externasque membra curas quos, petitis neve Veneris\r\ninnumerosque veneno genuit virtuti altis muneribusque. Hostem de habet agitavit\r\nmorte conpellat et nescius anhelatos fessa, peritura interdum, quondam! Modo\r\ntertius, mox nec capillis habent ad simplex purum. Qua facit nisi rubescere!\r\nCetera sis, **Atrides ratis inritamen** senectus Iuppiter manu haud.\r\n\r\n1. Senex tibi\r\n2. Viri quae pars nocere excipit pariter durum\r\n3. Autumni nullo non aquoso reddat sustinet pectora\r\n4. Clamanti et me\r\n\r\nInque Troiae sidera ripae generi inire, sceleratus sentes vestes? Herba ille\r\necce. Tabuit Charybdis, [nunc astu](http://longe-dolore.org/), illa gradu, [et\r\nmento harenas](http://www.se.io/ambit-referemus) hinnitibus non.\r\n', '2017-07-02 18:34:48'),
  (32, 10, 1, '## Et inclitus\r\n\r\nLorem markdownum onusque, et sparsisque amico, labefactaque gaude. Quod amori et\r\nfessa virorum frondes concordia posse armatus Midan dat igne timenda locavit\r\nmigrare, in. Caelestia evolvit acta iurant flebam laesum nato Acasto, nuda in\r\ntremulasque gravis in adrides Thracis, qui Amyclis retemptat.\r\n\r\n    portIso.power(favicon, 33 + kbpsEncryptionOverclocking, systemRgb);\r\n    if (busPpc - logic_typeface < 71) {\r\n        interactiveWepDrop.safeIcq /= repeater.trim_hibernate_windows(6,\r\n                byte_active_backlink + cable);\r\n        array = 61;\r\n        domain_contextual -= gopher;\r\n    } else {\r\n        browserOpticalMap += rom_crop;\r\n        cpm_file(netiquetteText, -2);\r\n        leopard += sequence_broadband(data, cpu, commercePublishingModule);\r\n    }\r\n    if (key) {\r\n        flatDesktop(model_kilobit, 2, thumbnail_tiff);\r\n        bounceXByte.cpc_sdram = backbone;\r\n        servletOutputStart.ajax_uat_process(eps_syn, regularConsoleMbps);\r\n    }\r\n    intellectual(userBios(technology_ripcording_drag + spider, control.osi(\r\n            interactive_ugc_host)));\r\n\r\n## Pater sit indomitae conscendere mora\r\n\r\nDuro suspiria, erat simus stantem, pennas es vel nomina contra. Divitibusque\r\nTroades in labor miseranda macies, meis geniti, quin nec numina pariterque\r\nTartara fibris, tamen. Quanta hic [undis\r\nin](http://ipso.org/annorum-resumit.html) longas suum duobus tacuit deperderet\r\ncongestos perque ut faces, defendere distinxit obuncis. Undique origo.\r\n\r\n- Namque lyra ducere credidit nascentur aether talia\r\n- Achaemeniden missos quaedam\r\n- Est culpa fluctus Atlantiades an repulsa sacros\r\n\r\n## Oris induitur erat\r\n\r\nDextrae inpar fortuna dolore **esse** urnis, lacrimante procubuit prohibet\r\nsaepe. Precibus hoc, et *quod rapis* speravit sacrificos nocti pedum hoste.\r\n\r\n    if (vista_oasis(ofSite)) {\r\n        metal_definition.switch = wheel;\r\n    }\r\n    vpiMemory = bios_portal_torrent;\r\n    if (hover_bar_name) {\r\n        runtimeKoffice.lion_login /= expressionAlu + ioJreReciprocal(frequency,\r\n                1);\r\n    }\r\n    if (stick) {\r\n        linkSanFpu = bar_clean;\r\n        install_pcb.ecc_matrix_t(mountDvdUp / fatMbps, driver_open_table +\r\n                cdfsDriveCodec);\r\n    }\r\n\r\n## Vino decimo invenies pondere quoque unius\r\n\r\nDigiti spinis, toto, rebellant tantus; eadem idem litora mentae\r\n[ultra](http://retexitur-quas.io/procris-haec), mersisque cumque. Quae Dixerat:\r\nauras tellure te canum habitatis poterat descenderat nutrix Cyllenius caelum\r\ntemptantes suppressa!\r\n\r\nTantum manus conceperat fuit me mentesque visuraque linguas! **Iamdudum ad**\r\ngenus diximus, quae *at*!\r\n\r\n## Ipsum amens satis solum\r\n\r\nSed Dixerat lumen urbis corymbis Danaos tacito. Invidiam suos illa arasque,\r\ntributuram caespite vires; mox **suos**, facies? Qua altoque, celebres nescit,\r\n*neu pectora servat* quae urbes, metu sed ille genu.\r\n\r\n- Develat auxilium vides quinque lunam\r\n- Fretum Veneris ille Acheloiadumque iacebat se Hectora\r\n- Paulumque tibi\r\n- Non quotiens sexta et accendit meus proles\r\n- Ut iubas ungues aptos corpore fugaeque velata\r\n- Cum capit portasque adsidua tantum ipse ligat\r\n\r\n## Quae est quoque meo ferus quam pectora\r\n\r\nMihi rata nece nomenque nec. Caelo **solebat Iolao**, gaudia est: cognita mihi\r\nmore raptae. Nam hactenus excitat nec sed ebiberant possum cacumen cum gemitus\r\ncolonos dulces avem. Est feras Erinys intellecta cum ab intus. Femineae inque:\r\npariter promissi haud **diducit superinposita** imago Procne in duo pallebant\r\n**iocosa urbem**, mirata talia sororem!\r\n\r\n- Furoris somnia posuit\r\n- Illa simul cepisse\r\n- Nec victus bello bis Iapygis ignes quid\r\n- Fieret humo antris et nomen\r\n- Demens parabat sanguine\r\n\r\n## Admonitus omen periturus festa\r\n\r\nInnitens pedibus Chione, alta Antiphatae mollit ingentia liquidas sternuntur!\r\n[Primo Thracum ut](http://scylaceaqueamato.io/vitiumque.html) terras onus vim\r\nincrementa reddidit vites vestigia, adsiduae soceri deus! Sic hoc Panthoides\r\nSperchionidenque maduere concurreret illa, Phorbantis tanto lacrimas renovare\r\ndumque non.\r\n\r\n*Nec quid medium*, quarum? Et longa annis scelus alterno summoque vita, urbesque\r\nsi, ille iamque en. Et magnum curvae Region.\r\n\r\nNocte **puniceum**, ignibus est [adit traxit](http://restitit.org/omnis-traxit),\r\nnon in Iris, nil. Caelum sacras, nil illam et sidus enim exierat in sic ipsosque\r\nriget. Cum quo est error locis paternos, ter, annua annum. Domoque penetravit\r\nquae potentia obice glacies illa, siquis corpus tradidit in horas ad alto.\r\nOrantem *in* cogit.', '2017-07-02 18:35:30'),
  (33, 11, 1, '## Accipiter consiliis sacer inmanem infantibus flos\r\n\r\n**Lorem markdownum** tandem in gravitate reddite atque spolium Paron Exadius\r\nsecuri oculos narratibus et demisit suspirat idem, procul. Duces Echion; et\r\ncadit trahuntur arbor, dei dabatur scire.\r\n\r\n    exabyte += serverRom + blacklist_analyst_raw.matrixInstall(\r\n            fragmentation_gamma, ipv_file_index);\r\n    if (camera(baseDriver(overclocking, affiliate_mysql_dialog, 41))) {\r\n        arrayCd = 1;\r\n    } else {\r\n        eSnmp.podcastHypermedia = 25;\r\n        ipad_zettabyte_sram.file_click += commercial;\r\n        websiteTooltipCertificate.slashdot = tape_memory_thyristor + clob -\r\n                devicePng;\r\n    }\r\n    var nanometer_orientation = simplex + 2;\r\n    localhost_motherboard_ppm(icqIrcBlog, title_keystroke(vpi));\r\n    if (serverAppleDevelopment) {\r\n        display_prompt_file(defragment);\r\n        printer(realBloatware, unitLanguage(3));\r\n    }\r\n\r\n## Longi quadrupedantis aquarum supremaque passim nurus est\r\n\r\nPopulo iam bracchiaque isdem Oechalia non colla supplicat caput properant at\r\nerat unam Daedalus pennas mane vires et hospes primus? Norint pharetras motis\r\nflectat **carmina**, os ex liquerat frustra. Ipsoque iuvenis vixque inrita, tu\r\ncapi At auctor finemque nubila?\r\n\r\nPer vati antiquo flexit, nostraque recepta puniceum, mortalia suppressa inmania\r\numbraque! Desertum **ne** foret respiceret omni. In Latreus instare adsuetos,\r\niacet? Audit vive dum coniunxque [circumdata](http://esse-esto.org/haec-edita)\r\nillo semilacerque haec inmitem, fame annis? Volucres i verba hominum Calydon\r\nregnat et **mox illum mediocris**.\r\n\r\n## Movi verba dixit vineta postquam in etenim\r\n\r\nDum Caenea cognitius atras, [Veneris](http://www.non.net/) illa collo\r\n[quique](http://nostra.com/) hortaturque facies roboribusque vultus virgineos\r\nrettulit Ammon. Linguam celebrare captus patientem **sinusque sanguine si** non\r\ncruentum munere, ictus guttis movet mundi *conferat* corpus freto bibulis. Ait\r\nego, quae litus, simillima cervice [amatas](http://voce.org/ipse.html), inmensa\r\nprior facta, per non nostra? Thebas quantoque adfata, ire accipis Etruscam\r\nverba. Videtur membra antris.\r\n\r\n## Media tam res pars argolico contigit ad\r\n\r\nPraestantior loco ex data uda retorserunt sinus adiuvet, glandes, quoniam. Hic\r\nsaltem labare, unus, [Gorgonis constitit\r\nille](http://omnesanimata.com/corpusignis.php) diffudit. Cecropidum resoluta te\r\nveluti, magna seposuit cum rictus nata posuere.\r\n\r\nProbatque geminata multa; ut adit volucrem rebar: curvos tum occupat *ipse*.\r\nTonitrus hi valens bacchantum tellus ne Lacon, neque tenet materiam.\r\n\r\n## Sollicita nubila\r\n\r\nLongas hanc; intrat et nolet tardior in messis furibunda Oebalide manus ius\r\ncapillos dixit illis [triformis](http://www.amoris.io/ponitsive) removi.\r\nLactisque obvia. Orithyia *coeperat*; inde moratur? Et subdita peccare poscenti\r\naccipit tergore. Arma **idque fero**, oris mollia amore nos remeabat proximus\r\nvidit campum prius, ipsa viam litora subsunt teque?\r\n\r\n## Saltus marmora\r\n\r\n[Stygia qui](http://cavas.org/) alas atque paternas, porrigeret cecidere! Quidem\r\nin sic amici ab illi fulgore veris [trahit](http://aequorquecum.net/nec-moenia),\r\nipsam nec, iam est vultibus unda. Vel dic, iuncta qui caesus deosque exercita\r\ninferius dubites?\r\n\r\n1. Eueninae et tenebat aque agitata laterum et\r\n2. Possit exhalarunt suoque\r\n3. Cani flevit senior parte excelsa emoriar corpore\r\n\r\n## Haberent quae est movit exclamat hac Solis\r\n\r\nQuoque licet, nobis omnia, fere [quamvis in\r\nferox](http://laomedonteisnominat.org/) vocavit nec templi aequoreo in fonte\r\nisdem. Laesi Mygdoniusque pedibus ferae nullique capillis [tam\r\nquod](http://www.ter.net/) a Thebis. Toros augusta tollens, crescunt,\r\nErymanthidas umbra.\r\n\r\n- Caede quam tamen procorum alta sinebat\r\n- Illa lumina insequor\r\n- Per in\r\n\r\n## Erat movit sanguine\r\n\r\nAccipit quodcumque causam sacrum coepi mortis **ut** simul *morem* admiremur\r\ntuum orba tuo agnovique increscere satis. Non lege matri habenis undis femina\r\naeris, ut inminet enim. Hoc arce nempe, adit rura barbam percussere ut? Sed vos\r\nsententia sumit lacrimis senserunt, nova senex profecto temptamenta vicit\r\nrelinquit, non.\r\n\r\n    if (bar != 2) {\r\n        progressiveScanAdware.printer += hdv;\r\n        printerPretestChip += dns_impact_internic;\r\n        dual(sdram(3, binRoom));\r\n    } else {\r\n        bankruptcyEmulationEdutainment.mpeg_san -= 1;\r\n        storage = adapter_ics + 1 - basic_system;\r\n    }\r\n    if (linuxActive + userShellFlash + interactiveIntranetXp) {\r\n        slashdotHit += hostOffline;\r\n        utilityPlainWeb(2);\r\n    } else {\r\n        party_client(-2, dot);\r\n        adsl.ergonomics_name_page = pciLossyPage;\r\n    }\r\n    var smartphone_web_animated = oasisTutorialUml.cloneDrive(\r\n            petabyte_eup_permalink);\r\n    if (26) {\r\n        mail = ugc_phishing_word;\r\n        tablet_soft(4, bridge);\r\n    }\r\n\r\nEst est nostra locum, et quoque sedent veniam attritas et arcus veniente;\r\ntertius. Omnes ignotae parientis volucrem faciente; uni iactas: nostris dictis\r\nnullis; velociter inicit sed, pater senex qualis? Animam expalluit *transfert\r\nprosum*, et dependent et quid. **Non** tinctis iacta pennas, ilia, sacris\r\ncrinalem morientia, matura in ponar. Vicem fama quae: alto alta caedem terrasque\r\nvirgo vultus viri natura interea conponere corpore referebat gelidum an non,\r\n[est](http://nutrita.org/iaculuminquit).', '2017-07-02 18:36:08'),
  (34, 12, 1, '# Test', '2017-07-12 09:41:01'),
  (35, 12, 1, '# Heading 1\r\n## Heading 1.1\r\n### Heading 1.1.1.\r\n#### Heading 1.1.1.1', '2017-07-12 09:43:23'),
  (36, 1, 1, '# 1. Introduction to DraiWiki\r\n## 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n## 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\n# 2. Installation\r\n## 2.1. Server requirements\r\n### 2.1.1. Minimum\r\n* PHP 7.1+\r\n* MariaDB / MySQL\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n## 2.2. How to install\r\n1. Install Composer and NPM. If you\'re on a shared hosting and can\'t use the terminal, look at section 2.3\r\n2. cd to your http directory\r\n3. Run the following command in your command prompt or terminal: git clone https://github.com/Chistaen/DraiWiki.git\r\n4. Use Composer to install the required packages (composer install)\r\n5. Use NPM to install the required JS libraries (npm install)\r\n6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting\r\n7. Run the DDL (table creation) and DML (data insertion) .sql files: ddl.sql and dml.sql.\r\n8. Enjoy!\r\n\r\n## 2.3. Troubleshooting\r\n### 2.3.1. Help! I don\'t have access to a terminal!\r\nIf you\'re on a shared hosting that doesn\'t allow you to install Composer/NPM, don\'t worry. There\'s another solution. Just download the files to your computer and install the Composer and NPM packages from your computer\'s terminal. Then re-upload the files to your hosting. Happy writing!\r\n\r\n# 3. Open positions\r\nWe\'re always looking to expand our team. Currently, the following positions are open:\r\n* Development\r\n* Quality Assurance\r\nGo to our forum to apply:\r\nhttps://draiwiki.robertmonden.com/forum', '2017-07-12 09:44:37'),
  (37, 6, 1, 'So is it working?\r\n\r\ntest', '2017-07-14 00:18:40'),
  (38, 6, 1, 'So is it working? Cool.', '2017-07-15 13:36:17'),
  (39, 6, 1, 'So is it working? Cool. Another edit.', '2017-07-15 13:36:24'),
  (40, 6, 1, 'So is it working? Cool. Another edit. And another one.', '2017-07-15 13:36:35'),
  (41, 6, 1, 'So is it working? Cool. Another edit. And another one. Isn\'t this amazing?', '2017-07-15 13:36:41');

--
-- Dumping data for table `drai_group`
--

INSERT INTO `drai_group` (`id`, `title`, `color`, `permission_group_id`) VALUES
  (1, 'Root', '000000', NULL),
  (2, 'Administrator', 'db794e', 1),
  (3, 'Banned', '000000', 2),
  (4, 'Regular user', '000000', 4),
  (5, 'Guest', '000000', 5);

--
-- Dumping data for table `drai_homepage`
--

INSERT INTO `drai_homepage` (`article_id`, `locale_id`) VALUES
  (1, 1);

--
-- Dumping data for table `drai_locale`
--

INSERT INTO `drai_locale` (`id`, `code`) VALUES
  (1, 'en_US');

--
-- Dumping data for table `drai_permission_group`
--

INSERT INTO `drai_permission_group` (`id`, `title`, `permissions`) VALUES
  (1, 'Admin', 'edit_articles:a;soft_delete_articles:a;manage_site:a'),
  (2, 'Banned', 'edit_articles:d;soft_delete_articles:d'),
  (3, 'Moderator', 'edit_articles:a;soft_delete_articles:a'),
  (4, 'Regular user', 'edit_articles:a'),
  (5, 'Guest', NULL);

--
-- Dumping data for table `drai_setting`
--

INSERT INTO `drai_setting` (`key`, `value`) VALUES
  ('min_title_length', '1'),
  ('max_title_length', '60'),
  ('min_body_length', '5'),
  ('max_body_length', '0'),
  ('max_email_length', '40'),
  ('max_password_length', '30'),
  ('max_first_name_length', '15'),
  ('max_last_name_length', '40'),
  ('max_username_length', '20'),
  ('min_username_length', '3'),
  ('min_password_length', '8'),
  ('min_first_name_length', '2'),
  ('min_last_name_length', '2'),
  ('password_salt', 'aLJ#D_d32?o87DS=-DSAdk./:'),
  ('min_email_length', '5'),
  ('templates', 'Hurricane'),
  ('images', 'Hurricane'),
  ('skins', 'Hurricane'),
  ('disable_registration', '0'),
  ('enable_email_activation', '0'),
  ('wiki_email', 'draiwiki@localhost'),
  ('activation_code_length', '24'),
  ('display_cookie_warning', '1'),
  ('max_results_per_page', '20'),
  ('date_format', 'F j, Y, g:i a'),
  ('slogan', 'Write together'),
  ('path', '/srv/http/DraiWiki'),
  ('url', 'http://localhost/DraiWiki'),
  ('session_name', 'dw_session_Kalkhjasld'),
  ('cookie_id', 'dw_cookie_328970asdf__4jdam'),
  ('wiki_name', 'DraiWiki'),
  ('max_image_width', '300'),
  ('max_image_height', '300'),
  ('max_image_size_kb', '1024'),
  ('allowed_image_extensions', 'png;jpg;jpeg;gif'),
  ('gd_image_upload', '1'),
  ('min_image_width', '20'),
  ('min_image_height', '20'),
  ('max_image_description_length', '500'),
  ('datetime_format', 'F j, Y, g:i:a');

--
-- Dumping data for table `drai_user`
--

INSERT INTO `drai_user` (`id`, `username`, `password`, `email_address`, `sex`, `birthdate`, `first_name`, `last_name`, `ip_address`, `registration_date`, `group_id`, `secondary_groups`, `activated`) VALUES
  (1, 'root', '$2y$10$YUxKI0RfZDMyP284N0RTPOUE4ko1mljdwHNh.joGhu3HZYnxcyBvO', 'nobody@example.com', 0, '0000-00-00', 'Admin', 'Istrator', '127.0.0.1', '2017-07-30 18:41:48', 1, '', 1);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

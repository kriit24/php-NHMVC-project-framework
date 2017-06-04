<div class="help-est" style="display:none;padding:20px;">
	<div>
		<b>Harilik</b>
		<p style="margin-top:10px;color:red;font-weight:bold;">NB! siin eelvaadet ei ole</p>
		<p>
			<ul>
				<li>Harilikus vaates saad asendada stiili peidetud parameetrid lihtsal kujul:
				<ul>
					<li>Värvi valiku korral saate värvi asendada "color picker" funkstiooniga</li>
					<li>Pildi valiku korral mige failidesse ja kopeerige lähtekood sobivasse lahtrisse</li>
				</ul>
				</li>
			</ul>
		</p>
	</div>

	<div>
		<b>Detailne</b>
		<p style="margin-top:10px;color:red;font-weight:bold;">NB! siin eelvaadet ei ole</p>
		<p>
			<ul>
				<li>Stiile ja scripte saab lisada:
				<ul>
					<li>Tegevus 1:
						<ul>
							<li>Lisa faili nimi</li>
							<li>"Lisa fail"</li>
							<li>Pärast lisamist saab faili sisu kopeerida</li>
						</ul>
					</li>
					<li>Tegevus 2:
						<ul>
							<li>Lisa faili asukoht, kust sisu tõmmata</li>
							<li>"Lisa fail"</li>
							<li>Pärast lisamist näeb faili sisu</li>
						</ul>
					</li>
				</ul>
				</li>
				<li>Detailses vaates saab muuta stiili css sisu</li>
				<li>Faili nime muutes jälgi failide järjekorda, vale järjekorra puhhul võib css lakata töötamast</li>
				<li>Kõigepealt on vajalikud välised library failid nagu bootstrap jne.</li>
				<li>Seejärel kodulehe stiili failid</li>
			</ul>
		</p>
	</div>

	<div>
		<b>Stiil</b>
		<p>
			<ul>
				<li>Stiili koodi lahtris on terve kodulehe stiil</li>
				<li>Pärast uuendamist koheselt avalehe stiili ei muudeta</li>
				<li>"Eelvaade" abil saab vaadata enne avalikustamist kodulehe stiili</li>
				<li>"Avalikusta" kopeerib failid live kataloogi</li>
				<li>"Taasta originaal" taastab algselt installeeritud stiili täies mahus (css, js, image ja design failid)</li>
			</ul>
		</p>
	</div>

	<div>
		<b>Stiili lisamine teiselt lehelt</b>
		<p>
			<ul>
				<li>Stiili saab lisada/muuta "Stiil" alammenüüst</li>
				<li>Sisesta veebilehe url kust stiil võetud, sisesta veebilehe lähtekood NB! mõlemad on vajalikud sest mõned lehed on blokeerinud curl päringud</li>
				<li>Vajuta nuppu "Uuenda"</li>
				<li>Vajuta nuppu "Eelvaade"</li>
				<li>Hiirega liikudes ekraanil näidatakse ära positsioon</li>
				<li>Vali sisulehe sobiv positsioon ja vajuta hiirega peale</li>
				<li>Sisesta sisulehe nimi, mis on ka ütlasi menüü nimi</li>
				<li>Vajuta "Lisa sisu" ja korda seda kõikide sisulehtede kohta eraldi</li>
				<li>Mine tagasi adminni ja võta menüü "Sisu" seal on näha kõik kopeeritud sisulehed</li>
				<li>Siit vaata edasi "Peidetus stiil" sektsiooni</li>
				<li>Pärast kustuta leht "UNDER CONSTRUCTION" ja tee teised lehed aktiivseks</li>
			</ul>
		</p>
	</div>

	<!--
	<div>
		<b>Peidetud stiil</b>
		<p>
			<ul>
				<li>Peidetud stiilis on vajalik kood kodulehe toimimise jaoks</li>
				<li>Kopeeri koodi rida "header" HEAD tagide vahele</li>
				<li>Kopeeri koodi rida "footer" VÕI "footer.min" enne /BODY tagi</li>
				<li>Peidetud koodis võetakse kõik .css ja .js failid automaatselt külge, mis on näha "Detailne" vaade</li>
				<li>Teised koodid tuleb manuaalselt külge panna, kui stiili üleslaadimisel seda automaatselt ei tehtud</li>
				<li>Või kopeeri osaline sisu vajalikesse kohtadesse</li>
			</ul>
		</p>
	</div>
	-->

	<div>
		<b>Taasta</b>
		<p>
			<ul>
				<li>Saad taastada stiili konkreetse kuupäeva seisuga</li>
				<li>Hetke stiilist kustutatakse kogu olemasolev ja asendatakse koopiaga</li>
			</ul>
		</p>
	</div>
</div>

<div class="help-eng" style="display:none;margin-top:20px;">
	eng
</div>

<?
$lang = _LANG == 'ee' ? 'est' : 'eng';
$this->script( '$(".help-'.$lang.'").show();' );
?>
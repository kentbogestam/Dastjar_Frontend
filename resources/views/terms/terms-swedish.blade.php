@extends('layouts.master')
@section('head-scripts')
	<style type="text/css">
		.top-container{
			padding-left: 50px; 
    		padding-right: 50px; 
		}

	#delete-me-btn{
		background: none;
    	color: #d25229;
	    border-radius: 10px;
	    border: none;
	}

	#delete_user_block{
		    display: inline-block;
		    position: absolute;
		    bottom: 10px;
		    left: 50%;
		    margin-left: -55px;
	}

	.ui-dialog{
		background-color: #fff !important;
	}

	.ui-controlgroup, #dialog-confirm + fieldset.ui-controlgroup {
    	width: 100%;
	}

	#dialog-confirm{
		display: none;
		/* color: #fff; */
	} 

	.ui-dialog .ui-dialog-buttonpane{
		text-align: center;
	}

	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset{
		float: none;
	}
	
	#dialog-confirm .ui-icon{
		float:left; 
		margin:12px 12px 20px 0;
		color: #fff;
	}

	#dialog-confirm .ui-icon-alert{
		color: #fff;
	}

	.ui-widget-overlay{
	    opacity: 0.5 !important;		
	}

	.dialog-no{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%) !important;
		color: #fff !important;
	}

	.dialog-no:hover{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%);
		color: #fff;
	}
	</style>
@endsection

@section('content')
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>Kvittens</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice 4.1.3  (Unix)">
	<META NAME="AUTHOR" CONTENT="Alireza Heidarian">
	<META NAME="CREATED" CONTENT="20180601;12020000">
	<META NAME="CHANGEDBY" CONTENT="kent ">
	<META NAME="CHANGED" CONTENT="20180620;12470300">
	<META NAME="AppVersion" CONTENT="16.0000">
	<META NAME="DocSecurity" CONTENT="0">
	<META NAME="HyperlinksChanged" CONTENT="false">
	<META NAME="LinksUpToDate" CONTENT="false">
	<META NAME="ScaleCrop" CONTENT="false">
	<META NAME="ShareDoc" CONTENT="false">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />

	<STYLE TYPE="text/css">
	</STYLE>
</HEAD>

	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('user-setting')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">{{ __('messages.Terms') }}</a></li>
			  <li class="done-btn" id="dataSave"></li> </ul>
			</div><!-- /navbar -->
		</div>
	</div>

<BODY LANG="sv-SE" TEXT="#000000" LINK="#0563c1" DIR="LTR">
<DIV TYPE=HEADER>
	<BR>
</DIV>

<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Restaurang
appen ”Anar” levereras av Dastjar AB (Dastjar) som är ett
utvecklings företag med egen service plattform. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dastjar
levererar digitala tjänster till små och mellanstora fö</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">retag.
Dastjars vision </SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">är
att hjälpa små och mellanstora företag att växa, samtidigt som vi
servar dig</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">
med en tj</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">änst
(app) som förenklar och sparar tid för dig vid</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">
best</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">ällning
i din favorit restaurang.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dastjar
tjänsten är positionsbaserad. Den spar din tid och tjänar dig bäst
där du finns. För att kunna göra så behöver vi ditt tillstånd
att lagra dina inloggningsuppgifter. Det hjälper oss att säkerställa
att din beställning levereras till just dig och ingen annan.</SPAN></FONT></FONT></FONT></FONT></P>
<UL>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">Mobil-appen
	”anar” är gratis att använda, som en extra tjänst till
	restauranger. </FONT></FONT>
	</P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">Tjänsten
	får inte användas i annat syfte än vad den är avsetts för. </FONT></FONT>
	</P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">Mobil-appen
	”anar” är avsett för att användas av restaurang besökarna
	och därmed får inte användas eller konfigureras för andra
	användningsområden, utan samtycke med Dastjar</FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">Dastjar
	tar inget ansvar för tjänsteavbrott eller annat problem pga fel
	utanför dess kontroll. Däremot Dastjar tar emot information om
	det, och vill göra sitt bästa att hjälpa till med lösningen.  </FONT></FONT>
	</P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none"><A NAME="_GoBack"></A>
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">Dastjar
	bär inte ansvaret för fel orsakade på grund av
	konfigurationsändringar.</FONT></FONT></P>
</UL>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none"><B>På
Dastjar respekterar vi och värnar om din personliga integritet. Här
beskriver vi när och varför vi samlar in, använder och delar
personlig information om personer som använder våra webbplatser,
mobilapplikationer eller använder våra digitala kanaler för att
kontakta oss (&quot;onlinetjänster&quot;).</B></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Genom
att använda våra onlinetjänster eller genom att kontakta oss
godkänner du denna sekretesspolicy och att vi behandlar dina
personuppgifter. Du godkänner också att vi kan använda digitala
kommunikationskanaler för att kontakta dig.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">För
frågor som rör personuppgifter och dataskydd, kontakta oss på 
</SPAN></FONT></FONT></FONT><FONT COLOR="#0563c1"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><U><A HREF="mailto:info@dastjar.com%2520"><FONT FACE="Helvetica, sans-serif">info@dastjar.com
</FONT></A></U></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none"><B>Hur
vi samlar in information, vad vi samlar in och varför vi samlar in
det</B></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western" STYLE="font-style: normal; text-decoration: none">
<FONT COLOR="#222222"><FONT SIZE=2>Personuppgifter</FONT></FONT></H1>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Detta
är grundläggande information som du ger oss när du skapat ett
användarkonto hos oss, så som namn, e-postadress eller 
telefonnummer samt en referens tillbaka till den autentisering
service som gett oss informationen om tillämpbart. Vi lagrar
kommunikation och korrespondens efter din leverans är fullbordad. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
uppgifter är dina personuppgifter, vilket innebär att de på din
begäran kan raderas.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för behandling: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">uppgifterna
är nödvändiga för att utföra vårt åtagande gentemot dig, för
att uppfylla våra lagliga skyldigheter och för vårt berättigade
intresse.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">förebyggande
av brott eller misstänkt olaglig verksamhet (bedrägeri).</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western" STYLE="font-style: normal"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Platsdata</SPAN></FONT></FONT></FONT></H1>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Om
du tillåter delning av din plats på din mobila enhet används din
position för att anpassa våra onlinetjänster och
användarupplevelsen såväl som för att förhindra bedrägerier. Vi
kan också använda din plats för att visa lokala erbjudanden till
dig. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">nödvändig
för att utföra vårt åtagande gentemot dig och tillgodose våra
berättigade intressen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">för
att förebygga brott eller misstänkt olaglig verksamhet (bedrägeri)
och att ge dig innehåll och uppdateringar under
beställningsprocessen samt för att underlätta effektiv drift och
uppföljning av vår verksamhet.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Transaktionsdata</SPAN></I></FONT></FONT></FONT></H1>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Detaljer
om de transaktioner du utför via våra tjänster. Detta används för
att hålla reda på dina beställningar, ge dig en orderhistorik, för
anpassning av innehåll, för att förbättra användarupplevelsen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">för
att uppfylla våra rättsliga skyldigheter, utföra vårt åtagande
gentemot dig och tillgodose våra berättigade intressen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Uppgifter
om användarbeteende</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Detaljer
om dina besök på våra onlinetjänster inklusive, men inte
begränsat till, trafikdata, och annan kommunikationsdata. Detta
används för att kunna förbättra våra onlinetjänster och att
upptäcka problem.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<H1 CLASS="western"><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Teknisk
information</SPAN></FONT></FONT></H1>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">nödvändig
för att utföra vårt åtagande gentemot dig och för vårt
berättigade intresse.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen: a</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">tt
tillhandahålla och förbättra användarupplevelsen och förebygga
brott eller misstänkt olaglig verksamhet (bedrägerier).</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Teknisk
data</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">När
du använder våra onlinetjänster kan vi samla in teknisk
information, så som vilken typ av mobilenhet du använder, en unik
enhetsidentifierare som IMEI-nummer, enhetstoken, MAC-adress för
enhetens trådlösa nätverksgränssnitt, IP-adress, mobilnummer som
används av enheten, mobilnätsinformation, versioner av
operativsystem och webbläsare. Detta används för att kunna
förbättra våra onlinetjänster och upptäcka problem.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">nödvändig
för att utföra vårt åtagande gentemot dig och tillgodose vår
berättigade intressen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen: </SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">förebyggande
av brott eller misstänkt olaglig verksamhet (bedrägeri).</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western"><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Betalningsdata</SPAN></I></FONT></FONT></H1>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Betalningsalternativen
kopplade till order och transaktioner är hanteras av vår
betalningsleverantör och kortdata lagras aldrig i någon av våra
onlinetjänster.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">:
att genomföra vårt åtagande mot dig och för att uppfylla våra
rättsliga skyldigheter.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Användning
av cookies</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
använder cookies för att identifiera, hålla reda på och räkna
besökare på onlinetjänster, för mer information besök vår
Cookies policy.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning: a</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">tt
genomföra vårt åtagande mot dig och för att uppfylla våra
rättsliga skyldigheter.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen: k</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">akor
används för att ge dig innehåll och uppdateringar under
beställningsprocessen och underlätta effektiv drift och drift av
vår verksamhet.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Känslig
personlig information</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
samlar inte medvetet eller avsiktligt data som klassificeras som
&quot;känsliga personuppgifter&quot;.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Hur
vi skyddar personuppgifter</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dina
personuppgifter skyddas av både tekniska och organisatoriska
åtgärder. Alla personuppgifter lagras säkert, åtkomst till data
övervakas och begränsas till de personer vars arbetsuppgifter
kräver det.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Hur
länge behåller vi dina uppgifter</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
behåller endast personuppgifter så länge som krävs för att
utföra våra åtaganden gentemot dig och för att uppfylla våra
lagliga skyldigheter (t.ex. bokföringsändamål).</SPAN></FONT></FONT></FONT></FONT></P>
<H1 CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none"><B>Dina
rättigheter</B></SPAN></I></FONT></FONT></FONT></H1>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Du
har rätt till;</SPAN></FONT></FONT></FONT></FONT></P>
<UL>
	<LI><P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">tillgång
	till dina uppgifter </SPAN></FONT></FONT></FONT></FONT>
	</P>
	<LI><P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">ta
	bort ditt data / konto</SPAN></FONT></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">att
	klaga till en tillsynsmyndighet&nbsp;</SPAN></FONT></FONT></FONT></FONT></P>
</UL>
<H1 CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none"><B>Ändringar
av policyn</B></SPAN></I></FONT></FONT></FONT></H1>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Denna
policy kan ändras när som helst. Vänligen kontrollera datumet för
senaste uppdateringen innan du lägger en ny order. Om du inte
godkänner ändringarna ska du sluta använda våra onlinetjänster.</SPAN></FONT></FONT></FONT></FONT></P>
<H1 CLASS="western"><EM><FONT COLOR="#222222"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><B>Cookies
Policy</B></FONT></FONT></FONT></EM></H1>
<P CLASS="western" STYLE="background: #ffffff"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
använder cookies för att identifiera </SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">anv</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">ä</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">ndare
i v</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">å</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">ra
onlinetj</SPAN></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">änster
vilket krävs för automatiskt inloggning för registrerade
användare. Om du ändrar inställningarna i din webbläsare till att
inte tillåta cookies kommer detta att påverka funktionaliteten i
våra tjänster och vi kan då inte garantera att våra tjänster
fungerar som utlovat.</SPAN></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
<BR><BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
<BR><BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
<BR><BR>
</P>
<DIV TYPE=FOOTER>
	 	@if(Auth::check())
 		<div id="delete_user_block"> 
					<form method="post" id="delete-me-form" action="{{ url('delete-me') }}" data-ajax="false">
						{{ csrf_field() }}
							<button type="submit" id="delete-me-btn" class="btn btn-danger">Avregistrera</button>	
 					</form>
		</div> 


	<div id="dialog-confirm" title="Delete Account">
			<p>Den här åtgärden kommer att ta bort alla dina personliga data i systemet.<br/><br/> Är du säker? Ja / Nej</p>
	</div>
 	@endif

	<div>
	<P ALIGN=RIGHT STYLE="margin-top: 1.15cm; margin-bottom: 0cm; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<SDFIELD TYPE=PAGE SUBTYPE=RANDOM FORMAT=PAGE>3</SDFIELD></P>
	<P STYLE="margin-bottom: 0cm; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<BR>
	</P>
	</div>
</DIV>


@endsection

@section('footer-script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script type="text/javascript">
		$('#delete-me-form').submit(function(event){
			event.preventDefault();

			$( "#dialog-confirm" ).dialog({
					resizable: false,
					modal: true,
					buttons: [						
						{
							text: "No",
							"class": 'dialog-no',
							click: function() {
								$(this).dialog("close");
							}					
						},
						{
							text: "Yes",
							"class": 'dialog-yes',
							click: function() {
								$(this).dialog("close");
								$('#delete-me-form').unbind().submit();
							}
						}
		        ]
				
			}); 
	
		});
</script>

@endsection		

@extends('layouts.master')
@section('head-scripts')
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>Kvittens</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice 4.1.3  (Unix)">
	<META NAME="AUTHOR" CONTENT="Alireza Heidarian">
	<META NAME="CREATED" CONTENT="20180629;16040000">
	<META NAME="CHANGEDBY" CONTENT="Alireza Heidarian">
	<META NAME="CHANGED" CONTENT="20180629;16140000">
	<META NAME="AppVersion" CONTENT="16.0000">
	<META NAME="DocSecurity" CONTENT="0">
	<META NAME="HyperlinksChanged" CONTENT="false">
	<META NAME="LinksUpToDate" CONTENT="false">
	<META NAME="ScaleCrop" CONTENT="false">
	<META NAME="ShareDoc" CONTENT="false">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />

	<style type="text/css">
		html { overflow-y: scroll; }

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

	.anar-logo {
	    max-width: 95px; 
/*    	margin-top: 5px;
*/    	margin-bottom: 2px;
	}
	</style>
@endsection

@section('content')
	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('user-setting')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">
			 	<img src="{{asset('images/logo.png')}}" class="anar-logo">
			 </a></li>
			  <li class="done-btn" id="dataSave"></li> </ul>
			</div><!-- /navbar -->
		</div>
	</div>

<DIV TYPE=HEADER>
		<p style="text-align: center;     font-size: 25px;
    font-weight: bold;">
		{{ __('messages.Terms') }}
	</p>
	<BR>
</DIV>

<H1 CLASS="western">Villkor och bestämmelser</H1>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
”<FONT FACE="Calibri, serif">anar”-appen (ANAR) förmedlar
beställningar av livsmedel från beställaren (nedan &quot;kunden&quot;)
till den av kunden valda restaurangen. Varje restaurang har specifika
öppettider och utkörningsområden. Vid lagd beställning ansvarar
varje enskild restaurang själv för att denna behandlas. ”ANAR”
försöker alltid upptäcka och varna om eventuella driftstörningar,
men ansvarar inte för fördröjd eller utebliven leverans till följd
av eventuella tekniska problem.</FONT></P>
<H2 CLASS="western"><B>Beställning</B></H2>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">För att få handla på ”ANAR” måste
du ha fyllt 18 år.</FONT></P>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">All personinformation som anges innan och
vid beställningen ska vara korrekt och tillhöra den som beställer
produkterna. ANAR ansvarar inte för utebliven leverans till följd
av otillräcklig eller felaktig information i beställningsformuläret.</FONT></P>
<H2 CLASS="western"><B>Produkter</B></H2>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">ANAR har som mål att alltid hålla alla
uppgifter på appen är uppdaterad för eventuella förändringar i
produktutbudet men vi reserverar oss för att vissa produkter kan
vara slut för den dagen. Varje enskild restaurang ansvarar själv
för att uppdatera sin meny, och meddela kunden om ändringar i
menyn.</FONT></P>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">ANAR arbetar för en hög kvalitet på de
produkter som erbjuds via appen och har därför ett stort intresse
av att ta del av kundens erfarenheter. Varje enskild restaurang har
dock det yttersta ansvaret för kvaliteten på sina produkter. Om
kunden har klagomål på enstaka produkter ska kunden i första hand
ta upp detta med den enskilda restaurangen.</FONT></P>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">Medan ANAR eftersträvar att alla priser
på hemsidan är helt korrekta, reserverar vi oss för eventuella fel
och möjligheten att någon restaurang har ändrat sina priser utan
att meddela ANAR.</FONT></P>
<H2 CLASS="western"><B>Betalning</B></H2>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">Alla beställningar via ANAR är
bindande. Vissa restauranger erbjuder sina kunder att via ANAR betala
med kontokort. För ytterligare information, se respektive
restaurangs meny och kassan.</FONT></P>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">Alla priser på ANAR är inklusive moms
om inget annat anges. Momsen på livsmedel, restaung och
cateringtjänster är 12 % och i övrigt är momsen 25 %. Kvitto och
momsspecifikation skickas via e-mail från respektive restaurang
eftersom det är dessa som säljer produkterna.</FONT></P>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">ANAR tjänsten är gratis för
konsumenten dvs du betalar direkt till restaurangen via ANAR och det
kostar dig som slutkund ingenting.</FONT></P>
<H2 CLASS="western"><B>Leverans</B></H2>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">För särskilt långa leveranstider har
restaurangen det yttersta ansvaret att meddela kunden om detta.</FONT></P>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.18cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR><BR>
</P>
<H2 CLASS="western"><B>Ångerrätt</B></H2>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">Försäljningen av mat via ANAR omfattas
inte av ångerrätt enligt distansavtalslagen (lag (2005:59) om
distansavtal och avtal utanför affärslokaler). Du kan läsa mer om
ångerrätt och vilka varor och tjänster som är undantagna från
ångerrätt på<FONT COLOR="#333333"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt">&nbsp;</FONT></FONT></FONT><A HREF="https://www.konsumentverket.se/"><FONT COLOR="#29b173"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><U>Konsumentverkets
hemsida</U></FONT></FONT></FONT></A><FONT COLOR="#333333"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt">.</FONT></FONT></FONT></FONT></P>
<H2 CLASS="western"><B>Villkorsändringar</B></H2>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">ANAR har rätt att ändra dessa villkor
utan att i förväg få ditt godkännande. ANAR informerar om
ändringen minst en vecka innan den träder i kraft. Informationen
lämnas på hemsidan: dastjar.com. Genom att fortsätta att nyttja
ANAR efter att ändringen trätt i kraft godkänner<FONT COLOR="#333333"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt">
du ändringen.</FONT></FONT></FONT></FONT></P>
<H2 CLASS="western"><B>Kontaktuppgifter</B></H2>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif">Vill du komma i kontakt med oss på
Kundtjänst når du oss via mejl&nbsp;info@dastjar.com.&nbsp;Vi
besvarar de flesta mejlfrågor inom 24 timmar under vardagar.</FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
<FONT FACE="Calibri, serif">Öppettider: måndag–fredag kl
09:00-18:00 </FONT>
</P>
<P CLASS="western" STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
<FONT FACE="Calibri, serif">ANAR utvecklas och drivs av Dastjar AB
med org. nummer 559123-5311</FONT></P>
<P CLASS="western" STYLE="margin-top: 0.18cm; margin-bottom: 0.42cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#333333"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><B>Vi
följer Allmänna reklamationsnämndens rekommendationer</B></FONT></FONT></FONT></FONT></P>
<H1 CLASS="western"><SPAN STYLE="text-decoration: none">Integritetspolicy</SPAN></H1>
<P CLASS="western"><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Restaurang
appen ”Anar” levereras av Dastjar AB (Dastjar) som är ett
utvecklings företag med egen service plattform. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dastjar
levererar digitala tjänster till små och mellanstora fö</SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN LANG="da-DK"><SPAN STYLE="text-decoration: none">retag.
Dastjars vision </SPAN></SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">är
att hjälpa små och mellanstora företag att växa, samtidigt som vi
servar dig</SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN LANG="da-DK"><SPAN STYLE="text-decoration: none">
med en tj</SPAN></SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">änst
(app) som förenklar och sparar tid för dig vid</SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN LANG="nl-NL"><SPAN STYLE="text-decoration: none">
best</SPAN></SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">ällning
i din favorit restaurang.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dastjar
tjänsten är positionsbaserad. Den spar din tid och tjänar dig bäst
där du finns. För att kunna göra så behöver vi ditt tillstånd
att lagra dina inloggningsuppgifter. Det hjälper oss att säkerställa
att din beställning levereras till just dig och ingen annan.</SPAN></FONT></FONT></FONT></FONT></P>
<UL>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 9pt">Mobil-appen
	”anar” är gratis att använda, som en extra tjänst till
	restauranger. </FONT></FONT>
	</P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 9pt">Tjänsten
	får inte användas i annat syfte än vad den är avsetts för. </FONT></FONT>
	</P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 9pt">Mobil-appen
	”anar” är avsett för att användas av restaurang besökarna
	och därmed får inte användas eller konfigureras för andra
	användningsområden, utan samtycke med Dastjar</FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 9pt">Dastjar
	tar inget ansvar för tjänsteavbrott eller annat problem pga fel
	utanför dess kontroll. Däremot Dastjar tar emot information om
	det, och vill göra sitt bästa att hjälpa till med lösningen.  </FONT></FONT>
	</P>
	<LI><P STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 9pt">Dastjar
	bär inte ansvaret för fel orsakade på grund av
	konfigurationsändringar.</FONT></FONT></P>
</UL>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none"><B>På
Dastjar respekterar vi och värnar om din personliga integritet. Här
beskriver vi när och varför vi samlar in, använder och delar
personlig information om personer som använder våra webbplatser,
mobilapplikationer eller använder våra digitala kanaler för att
kontakta oss (&quot;onlinetjänster&quot;).</B></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Genom
att använda våra onlinetjänster eller genom att kontakta oss
godkänner du denna sekretesspolicy och att vi behandlar dina
personuppgifter. Du godkänner också att vi kan använda digitala
kommunikationskanaler för att kontakta dig.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">För
frågor som rör personuppgifter och dataskydd, kontakta oss
på&nbsp;</SPAN></FONT></FONT><FONT COLOR="#0563c1"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><U><A HREF="mailto:info@dastjar.com%2520">info@dastjar.com
</A></U></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none"><B>Hur
vi samlar in information, vad vi samlar in och varför vi samlar in
det</B></SPAN></FONT></FONT></FONT></FONT></P>
<H2 CLASS="western"><SPAN STYLE="text-decoration: none"><B>Personuppgifter</B></SPAN></H2>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Detta
är grundläggande information som du ger oss när du skapat ett
användarkonto hos oss, så som namn, e-postadress eller 
telefonnummer samt en referens tillbaka till den autentisering
service som gett oss informationen om tillämpbart. Vi lagrar
kommunikation och korrespondens efter din leverans är fullbordad. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
uppgifter är dina personuppgifter, vilket innebär att de på din
begäran kan raderas.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för behandling:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;uppgifterna
är nödvändiga för att utföra vårt åtagande gentemot dig, för
att uppfylla våra lagliga skyldigheter och för vårt berättigade
intresse.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;förebyggande
av brott eller misstänkt olaglig verksamhet (bedrägeri).</SPAN></FONT></FONT></FONT></FONT></P>
<H2 CLASS="western"><SPAN STYLE="text-decoration: none">Platsdata</SPAN></H2>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Om
du tillåter delning av din plats på din mobila enhet används din
position för att anpassa våra onlinetjänster och
användarupplevelsen såväl som för att förhindra bedrägerier. Vi
kan också använda din plats för att visa lokala erbjudanden till
dig. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;nödvändig
för att utföra vårt åtagande gentemot dig och tillgodose våra
berättigade intressen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;för
att förebygga brott eller misstänkt olaglig verksamhet (bedrägeri)
och att ge dig innehåll och uppdateringar under
beställningsprocessen samt för att underlätta effektiv drift och
uppföljning av vår verksamhet.</SPAN></FONT></FONT></FONT></FONT></P>
<H2 CLASS="western"><A NAME="_GoBack"></A><SPAN STYLE="text-decoration: none">Transaktionsdata</SPAN></H2>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Detaljer
om de transaktioner du utför via våra tjänster. Detta används för
att hålla reda på dina beställningar, ge dig en orderhistorik, för
anpassning av innehåll, för att förbättra användarupplevelsen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;för
att uppfylla våra rättsliga skyldigheter, utföra vårt åtagande
gentemot dig och tillgodose våra berättigade intressen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Uppgifter
om användarbeteende</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Detaljer
om dina besök på våra onlinetjänster inklusive, men inte
begränsat till, trafikdata, och annan kommunikationsdata. Detta
används för att kunna förbättra våra onlinetjänster och att
upptäcka problem.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Teknisk
information</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;nödvändig
för att utföra vårt åtagande gentemot dig och för vårt
berättigade intresse.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;Att
tillhandahålla och förbättra användarupplevelsen och förebygga
brott eller misstänkt olaglig verksamhet (bedrägerier).</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Teknisk
data</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">När
du använder våra onlinetjänster kan vi samla in teknisk
information, så som vilken typ av mobilenhet du använder, en unik
enhetsidentifierare som IMEI-nummer, enhetstoken, MAC-adress för
enhetens trådlösa nätverksgränssnitt, IP-adress, mobilnummer som
används av enheten, mobilnätsinformation, versioner av
operativsystem och webbläsare. Detta används för att kunna
förbättra våra onlinetjänster och upptäcka problem.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;nödvändig
för att utföra vårt åtagande gentemot dig och tillgodose vår
berättigade intressen.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;förebyggande
av brott eller misstänkt olaglig verksamhet (bedrägeri).</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Betalningsdata</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Betalningsalternativen
kopplade till order och transaktioner är hanteras av vår
betalningsleverantör och kortdata lagras aldrig i någon av våra
onlinetjänster.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">:
Att genomföra vårt åtagande mot dig och för att uppfylla våra
rättsliga skyldigheter.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Användning
av cookies</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
använder cookies för att identifiera, hålla reda på och räkna
besökare på onlinetjänster, för mer information besök vår
Cookies policy.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dessa
data är anonymiserade och inte portabla.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Rättslig
grund för bearbetning:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;Att
genomföra vårt åtagande mot dig och för att uppfylla våra
rättsliga skyldigheter.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Berättigade
intressen:</SPAN></I></FONT></FONT><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">&nbsp;Kakor
används för att ge dig innehåll och uppdateringar under
beställningsprocessen och underlätta effektiv drift och drift av
vår verksamhet.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Känslig
personlig information</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
samlar inte medvetet eller avsiktligt data som klassificeras som
&quot;känsliga personuppgifter&quot;.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Hur
vi skyddar personuppgifter</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Dina
personuppgifter skyddas av både tekniska och organisatoriska
åtgärder. Alla personuppgifter lagras säkert, åtkomst till data
övervakas och begränsas till de personer vars arbetsuppgifter
kräver det.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none">Hur
länge behåller vi dina uppgifter</SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
behåller endast personuppgifter så länge som krävs för att
utföra våra åtaganden gentemot dig och för att uppfylla våra
lagliga skyldigheter (t.ex. bokföringsändamål).</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none"><B>Dina
rättigheter</B></SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Du
har rätt till;</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">-
tillgång till dina uppgifter </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">-
ta bort ditt data / konto</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">-
att klaga till en tillsynsmyndighet&nbsp;</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><I><SPAN STYLE="text-decoration: none"><B>Ändringar
av policyn</B></SPAN></I></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0.64cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#222222"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Denna
policy kan ändras när som helst. Vänligen kontrollera datumet för
senaste uppdateringen innan du lägger en ny order. Om du inte
godkänner ändringarna ska du sluta använda våra onlinetjänster.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><EM><FONT COLOR="#222222"><FONT FACE="inherit, serif"><FONT SIZE=2><B>Cookies
Policy</B></FONT></FONT></FONT></EM></FONT></FONT></P>
<P CLASS="western" STYLE="background: #ffffff"><FONT COLOR="#222222"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">Vi
använder cookies för att identifiera </SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="nl-NL"><SPAN STYLE="text-decoration: none">anv</SPAN></SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">ä</SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="it-IT"><SPAN STYLE="text-decoration: none">ndare
i v</SPAN></SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">å</SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="pt-PT"><SPAN STYLE="text-decoration: none">ra
onlinetj</SPAN></SPAN></FONT></FONT></FONT><FONT COLOR="#222222"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN STYLE="text-decoration: none">änster
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
							text: "Nej",
							"class": 'dialog-no',
							click: function() {
								$(this).dialog("close");
							}					
						},
						{
							text: "Ja",
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



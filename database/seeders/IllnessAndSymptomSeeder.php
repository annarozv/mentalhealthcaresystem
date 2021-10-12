<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MentalIllness;
use App\Models\Symptom;
use App\Models\IllnessSymptom;

class IllnessAndSymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MentalIllness::truncate();
        MentalIllness::create(array(
            'illness_name' => 'Depression',
            'illness_name_lv' => 'Depresija',
            'description' => 'Depression is a mood disorder characterised by lowering of mood, loss of interest and enjoyment, and reduced energy. It is not just feeling sad. There are different types and symptoms of depression. There are varying levels of severity and symptoms related to depression. Symptoms of depression can lead to increased risk of suicidal thoughts or behaviours.',
            'description_lv' => 'Depresija ir saslimšana, kas ietekmē emocijas, domas, uzvedību un fiziskās ķermeņa reakcijas. Ikviens kādā dzīves brīdī var justies noskumis vai nelaimīgs, taču tas ne vienmēr nozīmē, ka sākusies depresija. Depresija traucē pilnvērtīgi veikt ikdienas aktivitātes, var pat parādīties domas, ka dzīvot nav vērts. Svarīgi laikus vērsties pie speciālista, lai noteiktu depresijas veidu un saņemtu atbilstošu palīdzību. Mūsdienās depresija ir ārstējama slimība.'
        ));
        MentalIllness::create(array(
            'illness_name' => 'Paranoia',
            'illness_name_lv' => 'Paranoja',
            'description' => 'Paranoia is the irrational and persistent feeling that people are ‘out to get you’. Paranoia may be a symptom of conditions including paranoid personality disorder, delusional (paranoid) disorder and schizophrenia. Treatment for paranoia includes medications and psychological support.',
            'description_lv' => 'Slimību raksturo māniju ideju izskats, kas cilvēkam nepārtraukti aizņem centrālu pozīciju. Paranojas padara jūs redzēt visu, kas apstiprina jūsu pieņēmumus, lai viss būtu ļoti kritisks. Šajā situācijā cilvēks tiek klauvēts ļoti grūti, jo viņš praktiski nepieņem nekādus argumentus pret viņa fantāzijām. Pamazām paranojas parādās tālāk no reālās pasaules, paliekot tikai savā delīrijā. '
        ));
        MentalIllness::create(array(
            'illness_name' => 'Post-traumatic stress disorder',
            'illness_name_lv' => 'Pēctraumatiskā stresa traucējums',
            'description' => 'Post-traumatic stress disorder (PTSD) is a mental health condition that can develop as a response to people who have experienced any traumatic event. This can be a car or other serious accident, physical or sexual assault, war-related events or torture, or natural disasters such as bushfires or floods.',
            'description_lv' => 'Pēctraumas reakcijas attīstās pēc piedzīvota smaga un/vai ilgstoša traumatiska stresa. Traucējuma nosaukums „pēctraumatisks” norāda uz to, ka noteicošais traucējumā ir kāds notikums, piemēram: vardarbība, autoavārija, dabas katastrofas, ugunsgrēks, nelaimes gadījums, utml. Traumējošā notikuma laikā cilvēks pārdzīvo nāves briesmas vai kļūst par aculiecinieku citu cilvēku nāvei, pārciestas smagas fiziskas traumas vai vardarbības.Traumējošā situācija parasti notiek pēkšņi, negaidīti, tomēr uz pēctraumatiskā stresa sindromu var attiecināt arī ilgstoši notiekošu traumējošu situāciju, piemēram, karadarbība.'
        ));
        MentalIllness::create(array(
            'illness_name' => 'Obsessive compulsive disorder',
            'illness_name_lv' => 'Obsesīvi kompulsīvie traucējumi',
            'description' => 'Obsessive compulsive disorder (OCD) is an anxiety disorder. Obsessions are recurrent thoughts, images or impulses that are intrusive and unwanted. Compulsions are time-consuming and distressing repetitive rituals. Treatments include cognitive behaviour therapy (CBT), and medications',
            'description_lv' => 'Obsesīvi kompulsīvie traucējumi ir trauksmes traucējumi ar uzmācīgām domām, kas rada neērtumu, bažas, bailes un uztraukumu. Tajos indivīds veic atkārtotus uzvedības moduļus (apmātību un piespiedu uzvedību), lai mazinātu šo trauksmi. Obsesīvi kompulsīvo traucējumu simptomi izpaužas kā atkārtota un rūpīga roku mazgāšana, apkārtējās vides tīrīšana un kārtošana, vēlēšanās piešķirt katrai lietai savu vietu un panākt simetriju to izkārtošanā, atkārtota dažādu priekšmetu (piemēram, aizslēgtu durvju) pārbaudīšana, apsēstība ar seksuālām, vardarbīgām vai reliģiskām domām, izvairīšanās no atsevišķiem skaitļiem vai kādas darbības noteiktu reižu izpildīšana pirms aiziešanas gulēt. Slimnieks bieži izveido īpašus rituālus, bez kuru izpildīšanas viņš nespēj ķerties pie citiem sadzīves vai profesionāliem uzdevumiem un kuru pārtraukšanas gadījumā tie jāatsāk no jauna. Piemēram, vairākkārt ieslēgt un izslēgt gaismu, ienākot telpā un izejot no tās, nogaidīt, kamēr pa šoseju pabrauc noteiktas krāsas noteikts mašīnu skaits, pirms šķērsot ielu u.c. '
        ));
        MentalIllness::create(array(
            'illness_name' => 'Bipolar affective disorder',
            'illness_name_lv' => 'Bipolāri afektīvie traucējumi',
            'description' => 'Bipolar affective disorder is a type of mood disorder, previously referred to as ‘manic depression’. A person with bipolar disorder experiences episodes of mania (elation) and depression. The person may or may not experience psychotic symptoms. The exact cause is unknown, but a genetic predisposition has been clearly established. Environmental stressors can also trigger episodes of this mental illness.',
            'description_lv' => 'Bipolāri afektīvie traucējumi (agrāk tika dēvēti arī par maniakāli depresīvajiem traucējumiem) ir izteiktu garastāvokļa svārstību traucējumi.
Kā izpaužas garastāvokļa traucējumi – noskaņojums un aktivitātes līmenis periodiski (dažu nedēļu vai dažu vai vairāku mēnešu laikā) svārstās un būtiski ietekmē ikdienu.
Katrs cilvēks piedzīvo nelielas garastāvokļa svārstības, kad pozitīvs noskaņojums mijas ar nespēku, nevēlēšanos kaut ko darīt, sliktu noskaņojumu. Šīs nelielās svārstības noteikti nenorāda uz traucējuma esamību. Bipolāri afektīvie traucējumi ir nopietni smadzeņu darbības un hormonālo svārstību traucējumi.'
        ));
        MentalIllness::create(array(
            'illness_name' => 'Generalized Anxiety Disorder',
            'illness_name_lv' => 'Ģeneralizēta trauksme',
            'description' => 'Generalized Anxiety Disorder (GAD) is marked by excessive worry about everyday events. While some stress and worry are a normal and even common part of life, GAD involves worry that is so excessive that it interferes with a person\'s well-being and functioning. ',
            'description_lv' => 'Ģeneralizēta trauksme – ilgstoša (vismaz 6 mēnešus ilga), traucējoša, vieglas vai mērenas trauksmes sajūta ikdienišķās situācijās, ko pavada saspringums, nemiers un nespēja atslābināties, nervozitāte, nelaimes priekšnojauta. Bieži ir arī sirdsklauves, pastiprināta svīšana, muskuļu saspringums, tirpšanas sajūta ķermenī, reibonis, smakšanas sajūta. Pacientam ir grūti kontrolēt un apturēt trauksmi un satraucošās domas. Ģeneralizētai trauksmei līdzīgas izpausmes var novērot pie dažām fiziskām saslimšanām, piemēram, vairogdziedzera pastiprinātas darbības, paaugstināta asinsspiediena, kā arī dažu medikamentu lietošanas laikā. '
        ));
        MentalIllness::create(array(
            'illness_name' => 'Depersonalization/Derealization Disorder',
            'illness_name_lv' => 'Depersonalizācija/Derealizācija',
            'description' => 'Depersonalization/derealization disorder is characterized by experiencing a sense of being outside of one\'s own body (depersonalization) and being disconnected from reality (derealization). People who have this disorder often feel a sense of unreality and an involuntary disconnect from their own memories, feelings, and consciousness. ',
            'description_lv' => 'Depersonalizācija tiek definēta kā sajūta, it kā cilvēks tiktu atrauts no sevis, kā atslēgšanās no domām, jūtām un pieredzes. Daudzi to ir aprakstījuši, lai justos kā sapņojot, tikai viņu dzīve ir nemainīgs sapnis. Šīs grūtības atšķirt to, kas ir un kas nav reāls, ir lielākā daļa no tā, kas ir derealizācija . Tomēr kopēja derealizācijas pazīme ietver trakuma sajūtu, ko var attiecināt uz to, ka tiek zaudēta saķere ar realitāti. Apvienojoties, depersonalizācija-derealizācija ir sapņu, atraušanās no ķermeņa sajūtas un realitātes zaudēšanas apvienojums, kā arī pastāvīga sajūta, ka vērojat, kā viņi dzīvo savu dzīvi.'
        ));
        MentalIllness::create(array(
            'illness_name' => 'Schizophrenia',
            'illness_name_lv' => 'Šizofrēnija',
            'description' => 'Schizophrenia is a complex psychotic disorder characterised by disruptions to thinking and emotions, and a distorted perception of reality. Symptoms of schizophrenia vary widely but may include hallucinations, delusions, thought disorder, social withdrawal, lack of motivation and impaired thinking and memory. People with schizophrenia have a high risk of suicide. Schizophrenia is not a split personality.',
            'description_lv' => 'Šizofrēnija ir smaga psihiska saslimšana, kas ietekmē cilvēka smadzenes. Slimības rezultātā mainās cilvēka pasaules uztvere, domāšana, sajūtas un līdz ar to arī uzvedība. Veselam cilvēkam, lai cik savdabīga nebūtu viņa reakcija, saglabājas saistība ar realitāti - ir ārējs notikums (saruna, situācija), kuru viņš uztver, sajūt, domā un reaģē (iekšēji vai ārēji). Kad cilvēks slimo ar šizofrēniju, zūd iekšējais veselums, sakarības starp realitāti, domām, sajūtām un rīcību. Cilvēks vairs nespēj racionāli izvērtēt apkārt notiekošo un savas attiecības ar citiem, nespēj atšķirt iekšējo pieredzi no apkārt notiekošā. Zūd robeža starp sevi un citiem, starp savām un citu domām. Ilgstoši slimojot, parādās būtiskas izmaiņas cilvēka personībā.'
        ));


        Symptom::truncate();
        Symptom::create(array(
            'symptom_name' => 'Feelings of helplessness and hopelessness',
            'symptom_name_lv' => 'Bezcerības un bezpalīdzības sajūta',
            'description' => 'Hopelessness is the feeling that nothing can be done by anyone to make the situation better. People may accept that a threat is real, but that threat may loom so large that they feel the situation is hopeless. Helplessness is the feeling that they themselves have no power to improve their situation.',
            'description_lv' => 'Sajūta, ka nekas nevar uzlabot situāciju. Ticība, ka cilvēkām nav spēka, lai kaut ko mainītu un palīdzētu sev.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Loss of interest in daily activities',
            'symptom_name_lv' => 'Intereses trūkums',
            'description' => 'You don’t care anymore about former hobbies, pastimes or social activities. You’ve lost your ability to feel joy and pleasure.',
            'description_lv' => 'Vienaldzīga attieksme pret visu, kas āgrāk bija patīkams. Samazināta interese par ikdienas aktivitātēm'
        ));
        Symptom::create(array(
            'symptom_name' => 'Loss of energy',
            'symptom_name_lv' => 'Enerģijas izsīkums',
            'description' => 'Feeling fatigued, sluggish, and physically drained. Your whole body may feel heavy, and even small tasks are exhausting or take longer to complete.',
            'description_lv' => 'Ilgstošs nogurums un bezspēks, gan emocionāls, gan fizisks. Sajūta ir tāda, ka pilnīgi visas organisma enerģija ir izsmelta.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Concentration problems',
            'symptom_name_lv' => 'Koncentrēšanās problēmas',
            'description' => 'Trouble focusing, making decisions, or remembering things.',
            'description_lv' => 'Grūtības koncentrēties, pieņemt lēmumus un atcerēties lietas.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Sleep Disorders',
            'symptom_name_lv' => 'Miega traucējumi',
            'description' => 'Depending on the type of sleep disorder, people may have a difficult time falling asleep and may feel extremely tired throughout the day. The lack of sleep can have a negative impact on energy, mood, concentration, and overall health.',
            'description_lv' => 'Traucēta miega jeb bezmiega gadījumā mēdz būt grūtības iemigt, miegs nesniedz spirdzinājumu vai ir traucēta miega saglabāšana – bieža pamošanās naktsmiera laikā, nespēja no jauna iemigt, pārāk agra pamošanās. Parastās sūdzības ir par hronisku nogurumu un miegainību.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Appetite Disorders',
            'symptom_name_lv' => 'Ēstgribas traucējumi',
            'description' => 'Loss of appetite means you don\'t have the same desire to eat as you used to. Signs of decreased appetite include not wanting to eat, unintentional weight loss, and not feeling hungry. Increased appetite is also a sign of problems.',
            'description_lv' => 'Apetītes zudums, svara zudums - nav vēlmes ēst kā āgrāk. Pārāk liela apetīte arī ir slikts signāls.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Mistrust, doubts and suspicions',
            'symptom_name_lv' => 'Neuzticība, šaubas un aizdomas',
            'description' => 'Feeling that people are using hints and double meanings to secretly threaten you or make you feel bad or people are trying to take your money or possessions.',
            'description_lv' => 'Cilvēkam šķiet, ka citi cilvēki viņu aprunā, mēģina piekrāpt vai izdarīt kaut ko sliktu. Nespēja uzticēties citiem, pat tuviem cilvēkiem.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Belief in conspiracy theories',
            'symptom_name_lv' => 'Ticība sazvērestības teorijām',
            'description' => 'Person believes in conspiracy, that some group is trying to do something unlawful or harmful to the ordinary people.',
            'description_lv' => 'Cilvēks jūtas vientuļš, nesaprasts, pievilts un netiek galā ar savām problēmām. Sazvērestības teorijas tad kļūst par tādu kā spieķi vai glābšanas riņķi, kas vismaz palīdz problēmas saprast, ja ne risināt.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Aggression',
            'symptom_name_lv' => 'Agresija',
            'description' => 'Aggression refers to a range of behaviors that can result in both physical and psychological harm to yourself, others, or objects in the environment. Aggression centers on hurting another person either physically or mentally.',
            'description_lv' => 'No psihiatrijas viedokļa personas agresija tiek uzskatīta par psiholoģiskās aizsardzības metodi pret traumatisku un nelabvēlīgu situāciju. Tā var būt arī psiholoģiskās relaksācijas metode, kā arī sevis apliecināšana. Agresija nodara kaitējumu ne tikai indivīdam, dzīvniekam, bet arī nedzīvajam objektam. Agresīva uzvedība cilvēkiem tiek apskatīta šķērsgriezumā: fiziska - verbāla, tieša - netieša, aktīva - pasīva, labdabīga - ļaundabīga. '
        ));
        Symptom::create(array(
            'symptom_name' => 'Feeling of guilt',
            'symptom_name_lv' => 'Nepārtraukta vainas izjūta',
            'description' => 'Guilt is an emotional state where we experience conflict at having done something that we believe we should not have done (or conversely, having not done something we believe we should have done). This can give rise to a feeling state which does not go away easily and can be difficult to endure.',
            'description_lv' => 'Neatbilstošas vainas izjūtas rezultātā cilvēks kļūdaini uzņemas atbildību par situāciju vai pārvērtē ciešanu iemeslu. Šāda vainas izjūta var būt ļoti postoša, ja tai nepievērš pietiekamu uzmanību un to nerisina. Pārmērīga un neatbilstoša vainas izjūta var būt saistīta ar dažādām garīgās veselības traucējumiem, piemēram trauksmes sajūtu, depresiju, obsesīvi kompulsīvajiem traucējumiem vai disforiju, t.i., nepārtrauktu neapmierinātības izjūtu. Tā bieži sastopama cilvēkiem ar traumatisku pieredzi vai neatbilstošu audzināšanu bērnībā.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Anxiety',
            'symptom_name_lv' => 'Trauksme',
            'description' => 'Anxiety is a feeling of unease, such as worry or fear, that can be mild or severe. Everyone has feelings of anxiety at some point in their life. For example, you may feel worried and anxious about sitting an exam, or having a medical test or job interview. During times like these, feeling anxious can be perfectly normal. But some people find it hard to control their worries. Their feelings of anxiety are more constant and can often affect their daily lives.',
            'description_lv' => 'Trauksme ir sajūtas vai emocijas, kuras vajadzētu pieņemt kā normālu pazīmi. Būtībā bez trauksmes cilvēks nevar dzīvot, jo tā ir kā signāls organismam, ka apkārtējā vidē vai iekšējā pasaulē (psihē) ir kaut kāds drauds cilvēka iekšējai pasaulei. Līdzīgi kā cilvēkam slimības gadījumā paaugstinās ķermeņa temperatūra – visiem ir zināms, ka tas ir signāls par veselības pasliktināšanos. Uztraukties vajadzētu gadījumos, kad trauksme ir pārāk izteikta, bieža un neadekvāta konkrētajai situācijai. Piemēram, rodas iekāpjot liftā, ieejot lielveikalā, apmeklējot teātri vai kinoseansa laikā.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Obsessive thoughts',
            'symptom_name_lv' => 'Uzmācīgas domas',
            'description' => 'Obsessive thinking is a series of thoughts that typically recur, often paired with negative judgements. Many times there is an inability to control these persistent, distressing thoughts and the severity can range from mild but annoying, to all-encompassing and debilitating.',
            'description_lv' => 'Uzmācīgas domas (obsesijas) pilnībā pārņem cilvēka apziņu un no tām ir visai grūti atbrīvoties. Pats graujošākais šajā stāvoklī ir tas, ka cilvēks lieliski apzinās visas notiekošā „šausmas”, bet absolūti neko nevar ar to padarīt. Pat tad, kad cilvēks ir sapratis, ka tamlīdzīgas domas ir neracionālas un nepamatotas, viņš nespēj ar tām tikt galā.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Impulsivity',
            'symptom_name_lv' => 'Impulsivitāte',
            'description' => 'Impulsivity is the tendency to act without thinking, for example if you blurt something out, buy something you had not planned to, or run across the street without looking.',
            'description_lv' => 'Uzvedības netīšums, tieksme darboties ātri, reaktīvi, vadoties no pēkšņas ierosmes, impulsa, neapzināti, bez refleksijas. Mēdz tērēties, izšķiest naudu, spontāni aizņemties, pārdot.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Exaggerated Feelings of Self Importance',
            'symptom_name_lv' => 'Pārspīlēta sevis svarīguma izjūta',
            'description' => 'The idea that your experiences and needs are more important than those of others. This also leads you to the belief that others care more than they do about what’s happening to you, what you look like, and what you’re doing.',
            'description_lv' => 'Pārliecība par to, ka tavas domas un dzīve ir vairāk svarīgas nekā citu cilvēku dzīves. Pārliecība, ka visa pasaule griežas ap tevi.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Lack of emotions',
            'symptom_name_lv' => 'Emociju trūkums',
            'description' => 'Feeling emotionally numb, distancing from emotions.',
            'description_lv' => 'Distancēšanās no savam emocijām, savu emociju nepārdzīvošana.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Losing touch with reality',
            'symptom_name_lv' => 'Realitātes zaudēšanas izjūta',
            'description' => 'Feeling detached from your thoughts, feelings and body. Feeling that everything is not real.',
            'description_lv' => 'Distancēšanās no savām jūtam un ķermeņa. Pazūd sakars ar realitāti.'
        ));
        Symptom::create(array(
            'symptom_name' => 'Hallucinations',
            'symptom_name_lv' => 'Halucinācijas',
            'description' => 'Hallucinations are where someone sees, hears, smells, tastes or feels things that don\'t exist outside their mind. They\'re common in people with schizophrenia, and are usually experienced as hearing voices. Hallucinations can be frightening, but there\'s usually an identifiable cause.',
            'description_lv' => 'Halucinācijas ir uztveres stāvoklis, kurā dzīvā organisma maņu orgāni saņem kairinājumus bez īsta ārējā objekta esamības, bet kas pašai dzīvajai būtnei var likties kā daļa no ārējās pasaules vizuālās īstenības. Halucinācijas izšķir atsevišķi no citiem līdzīgiem uztveres stāvokļiem, piemēram, sapņošanu, ilūzijām, iztēli un pseidohalucinācijām. Visbiežāk sastopamās ir dzirdes halucinācijas. Bieži ir sastopamas arī redzes un smaržas halucinācijas. '
        ));


        IllnessSymptom::truncate();
        IllnessSymptom::create(array(
            'illness_id' => 1,
            'symptom_id' => 1
        ));
        IllnessSymptom::create(array(
            'illness_id' => 1,
            'symptom_id' => 2
        ));
        IllnessSymptom::create(array(
            'illness_id' => 1,
            'symptom_id' => 3
        ));
        IllnessSymptom::create(array(
            'illness_id' => 1,
            'symptom_id' => 4
        ));
        IllnessSymptom::create(array(
            'illness_id' => 1,
            'symptom_id' => 5
        ));
        IllnessSymptom::create(array(
            'illness_id' => 1,
            'symptom_id' => 6
        ));
        IllnessSymptom::create(array(
            'illness_id' => 1,
            'symptom_id' => 10
        ));
        IllnessSymptom::create(array(
            'illness_id' => 2,
            'symptom_id' => 7
        ));
        IllnessSymptom::create(array(
            'illness_id' => 2,
            'symptom_id' => 8
        ));
        IllnessSymptom::create(array(
            'illness_id' => 2,
            'symptom_id' => 9
        ));
        IllnessSymptom::create(array(
            'illness_id' => 2,
            'symptom_id' => 12
        ));
        IllnessSymptom::create(array(
            'illness_id' => 3,
            'symptom_id' => 5
        ));
        IllnessSymptom::create(array(
            'illness_id' => 3,
            'symptom_id' => 6
        ));
        IllnessSymptom::create(array(
            'illness_id' => 3,
            'symptom_id' => 10
        ));
        IllnessSymptom::create(array(
            'illness_id' => 3,
            'symptom_id' => 11
        ));
        IllnessSymptom::create(array(
            'illness_id' => 3,
            'symptom_id' => 1
        ));
        IllnessSymptom::create(array(
            'illness_id' => 3,
            'symptom_id' => 2
        ));
        IllnessSymptom::create(array(
            'illness_id' => 4,
            'symptom_id' => 10
        ));
        IllnessSymptom::create(array(
            'illness_id' => 4,
            'symptom_id' => 11
        ));
        IllnessSymptom::create(array(
            'illness_id' => 4,
            'symptom_id' => 12
        ));
        IllnessSymptom::create(array(
            'illness_id' => 5,
            'symptom_id' => 2
        ));
        IllnessSymptom::create(array(
            'illness_id' => 5,
            'symptom_id' => 3
        ));
        IllnessSymptom::create(array(
            'illness_id' => 5,
            'symptom_id' => 4
        ));
        IllnessSymptom::create(array(
            'illness_id' => 5,
            'symptom_id' => 13
        ));
        IllnessSymptom::create(array(
            'illness_id' => 5,
            'symptom_id' => 14
        ));
        IllnessSymptom::create(array(
            'illness_id' => 6,
            'symptom_id' => 1
        ));
        IllnessSymptom::create(array(
            'illness_id' => 6,
            'symptom_id' => 5
        ));
        IllnessSymptom::create(array(
            'illness_id' => 6,
            'symptom_id' => 6
        ));
        IllnessSymptom::create(array(
            'illness_id' => 6,
            'symptom_id' => 10
        ));
        IllnessSymptom::create(array(
            'illness_id' => 6,
            'symptom_id' => 11
        ));
        IllnessSymptom::create(array(
            'illness_id' => 6,
            'symptom_id' => 12
        ));
        IllnessSymptom::create(array(
            'illness_id' => 7,
            'symptom_id' => 15
        ));
        IllnessSymptom::create(array(
            'illness_id' => 7,
            'symptom_id' => 16
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 4
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 5
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 7
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 8
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 9
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 12
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 16
        ));
        IllnessSymptom::create(array(
            'illness_id' => 8,
            'symptom_id' => 17
        ));
    }
}

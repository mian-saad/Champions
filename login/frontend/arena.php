<?php

$plugin_path = plugin_dir_path( dirname(__FILE__, 1));
$string_file = json_decode(file_get_contents( $plugin_path."assets/base/en/alert_strings.json"), true);
$intro = $string_file['intro'];
$bullet1 = $string_file['bullet1'];
$bullet2 = $string_file['bullet2'];



echo "
        <div class=\"card-body\" id=\"contentBox\">
        <form>
            <select name='lang' id='lang_select'>
                <option value='en'>🇬🇧 English</option>
                <option value='ge'>🇩🇪 German</option>
                <option value='hun'>🇭🇺 Hungarian</option>
                <option value='pol'>🇵🇱 Polish</option>
                <option value='ro'>🇷🇴 Romanian</option>
            </select>
        </form><br>

        <!-- English -->
        <div id='en' class='lan' style='display: inline; text-align: justify;'>
            <h1> Welcome to ARENA Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <br>
            <div id='login_button_pane'>
                <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
                <a class=\"login_links\" id='start_registration' >Registration</a>
            </div>            
        </div >
       
       <!-- German -->
        <div id='ge' class='lan' style='display: none; text-align: justify;'>
            <h1> Willkommen bei ARENA-Modul</h1>
            <p>Hier beschäftigen sich Gruppen multidisziplinärer Expert:innen mit Fällen von Radikalisierung und/oder Polarisierung. Sie arbeiten gemeinsam an Lösungen und geben Empfehlungen an First-Line Practitioners (kurz: FLPs). Registrieren Sie sich als FLP/Expert:in und stellen Sie uns Ihr Fachwissen zur Verfügung. Tauschen Sie sich mit anderen Fachleuten aus, sichten Sie Fälle von Radikalisierung und/oder Polarisierung, geben Sie Handlungsempfehlungen, teilen Sie Ihre Best Practice Erfahrungen und unterstützen Sie Kolleg:innen in ihrer täglichen Arbeit gegen Radikalisierung und/oder Polarisierung.</p>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
            <a class=\"login_links\" id='start_registration' >Regstrierung</a>
        </div >

        <!-- Hungarian -->
        <div id='hun' class='lan' style='display: none; text-align: justify;'>
            <h1> Üdvözöljük az ARENA modulban</h1>
            <p>Az ARENA platformot a radikalizálódás és a polarizáció kezelésével foglalkozó szakértők összekapcsolására hozták létre. Ez a több ügynökséget magában foglaló szakértői csoport együttműködve hatékony és időszerű válaszokat és megelőző intézkedéseket hoz létre a radikalizálódás, a gyűlölet és a szélsőségesség kérdéseinek és eseményeinek kezelése érdekében, valamint ajánlásokat és támogatást nyújt az elsőrendű szakembereknek. Az ARENA használatához: Regisztráljon First-Line Practitioner (FLP) / szakértőként. Az FLP-k között lehetnek közösségi munkások, szociális munkások, tanárok, ifjúsági dolgozók, helyi önkormányzati tisztviselők, bűnüldöző tisztviselők és még sok más. Itt röviden áttekintheti munkáját és szakterületét. Kapcsolatba léphet más szakértőkkel, megoszthatja és felülvizsgálhatja a radikalizálódás és / vagy a polarizáció eseteit, javaslatokat tehet és megoszthatja a bevált gyakorlatokat a megelőzés és a reagálás javításának elősegítése érdekében, valamint támogathatja első vonalbeli orvosait</p>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Belépés</a><br>
            <a class=\"login_links\" id='start_registration' >Regisztráció</a>
        </div >
        
        <!-- Polish -->
        <div id='pol' class='lan' style='display: none; text-align: justify;'>
            <h1> Witamy w module ARENA</h1>
            <p>Platforma ARENA ma za zadanie ułatwiać współpracę ekspertom i ekspertkom z różnych dziedzin w zakresie zapobiegania i przeciwdziałania polaryzacji społecznej i radykalizacji. Za jej pośrednictwem międzysektorowa grupa specjalistów i specjalistek ma możliwość wspólnie pracować nad rekomendacjami dotyczącymi zdarzeń związanych z radykalizacją i ekstremizmem. Następnie rekomendacje przesyłane są osobom zgłaszającym zdarzenia lub incydenty związane z radykalizacją na platformie ALERT. Aby korzystać z ARENY, zarejestruj się na platformie jako ekspert(-ka), pokrótce opisując swoją dziedzinę pracy oraz doświadczenie w zakresie zapobiegania i przeciwdziałania radykalizacji. Następnie nawiąż kontakt z innymi specjalistami i specjalistkami z różnych dziedzin, aby wspólnie wymieniać się opiniami i współpracować przy tworzeniu rekomendacji dla zgłaszanych zdarzeń.  </p>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
            <a class=\"login_links\" id='start_registration' >Rejestracja</a>
        </div >
        
        <!-- Romanian -->
        <div id='ro' class='lan' style='display: none; text-align: justify;'>
            <h1> Bine ați venit la modulul ARENA</h1>
            <p>Platforma ARENA a fost creată pentru a pune în legătură experți pe probleme de radicalizare și polarizare. Acest grup  multi-agenție de experți lucrează împreună pentru a găsi răspunsuri actuale și eficiente și măsuri preventive contra incidentelor de radicalizare, ură și extremism și pentru a aduce recomandări și suport pentru practicieni. Pentru a folosi ARENA puteți să: - vă înregistrați ca First Line Practioner (FLP)/Expet. În categoria FLP sunt incluși lucrători în comunitate, lucrători sociali, profesori, lucrători de tineret, oficiali guvernamentali locali, forțe de ordine etc. Aici puteți împărtăși pe scurt despre ariile în care aveți experiență și expertiză; - vă intrați în legătură cu alți experți, să împărtășiți și să analizați cazuri de radicalizare și polarizare, să recomandați acțiuni și să împărtășiți bune practici pentru prevenție și răspuns și să veniți în sprijinul grupului de FLP.</p>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
            <a class=\"login_links\" id='start_registration' >Înregistrare</a>
        </div >
        
        </div>
        ";

        echo "
            <script src=\"https://code.jquery.com/jquery-3.5.1.min.js\"></script>
            <script>
            
                $(document).ready(function(){
                    $('#lang_select').on('change', function() {
                        $('.lan').hide();
                        $('#'+ $(this).val()).show();
                    });
                });
          
             </script>
        ";


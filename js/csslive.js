            // Constants (should be defined by PHP)
            let webroot = "https://www.spacemy.xyz"; //"https://spacemy.xyz";
            let profile_id = <?php echo getIDFromUser($_SESSION['siteusername'], $conn) ?>;

            // Global vars
            var profile_window;
            var chkclose_timer;

            function freepfwin() {
                // Enable Open Preview button
                document.getElementById("prevbtn").style.display = null;

                // Disable changes being sent to preview
                document.getElementById("cssarea").onkeyup = null;
            }

            function loadpfwin() {
                profile_window = window.open( `${webroot}/preview.php?id=${profile_id}&ed`, "SpaceMy: Preview CSS", "width=920,height=600" );

                profile_window.window.onload = () => {
                    // Disable Open Preview button
                    document.getElementById("prevbtn").style.display = "none";

                    // Get style from window
                    document.getElementById("cssarea").innerHTML = profile_window.document.getElementsByTagName("style")[0].innerHTML;

                    // Any changes change css on preview
                    document.getElementById("cssarea").onkeyup = () => {
                        profile_window.document.getElementsByTagName("style")[0].innerHTML = document.getElementById("cssarea").value;
                    };
                };

                chkclose_timer = setInterval(()=>{
                    if (profile_window.closed) {
                        console.log("closed")
                        clearInterval(chkclose_timer);
                        freepfwin();
                    }
                }, 100);
            };
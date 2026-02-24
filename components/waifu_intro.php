<div id="waifu-overlay" style="position:fixed; top:0; left:0; width:100%; height:100vh; background:#050505; z-index:999999; display:flex; flex-direction:column; justify-content:flex-end; transition: opacity 1s ease-out;">
    
    <div id="waifu-bg" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:-1;"></div>

    <div id="waifu-container" style="
    position: absolute; 
    bottom: 120px; /* Di atas chatbox sedikit biar gak ketutup */
    right: 0; 
    width: 100%; /* Default mobile: full width */
    max-width: 500px; /* Batas maksimal biar gak kegedean di desktop */
    aspect-ratio: 1 / 1; /* Paksa tetap kotak 1:1 */
    opacity: 0; 
    transition: all 1s ease-in-out; 
    transform: translateY(20px);
    z-index: 5;
    display: flex;
    justify-content: center;
    align-items: flex-end;
">
    <img src="assets/waifu.png" alt="Waifu" style="
        width: 90%; /* Kasih sedikit ruang napas */
        height: 90%; 
        object-fit: contain; /* Gambar gak bakal gepeng, tetep 1:1 di dalam box */
        filter: drop-shadow(0 0 20px rgba(0,210,255,0.5));
    ">
</div>

    <div id="chat-box" style="margin: 20px; background: rgba(15, 15, 15, 0.9); border: 2px solid #00d2ff; border-radius: 15px; padding: 25px; position: relative; z-index: 10; box-shadow: 0 0 30px rgba(0,0,0,1);">
        <div id="waifu-name" style="position:absolute; top:-15px; left:20px; background:#00d2ff; color:#000; padding:2px 15px; font-weight:bold; border-radius:5px; font-family: 'Courier New', monospace;">Aquarian Cecilia</div>
        
        <p id="typing-text" style="color:#fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.1rem; min-height: 50px; margin-bottom: 20px;"></p>
        
        <div id="options-container" style="display:flex; gap:10px; flex-wrap:wrap;">
            </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Cek apakah sudah pernah intro di session ini
    if (sessionStorage.getItem('introDone')) {
        document.getElementById('waifu-overlay').style.display = 'none';
        return;
    }

    const textElement = document.getElementById('typing-text');
    const waifuImg = document.getElementById('waifu-container');
    const optionsContainer = document.getElementById('options-container');

    const script = [
        {
            text: "Initializing system protocols... Oh, wait! You're early. Good morning, Master.",
            options: [{ text: "Morning! Status report?", next: 1 }]
        },
        {
            text: "All Python engines are primed and the server is running smooth on your Helio G99 power. Ready to automate the world today?",
            options: [
                { text: "Let's do this!", next: "exit" },
                { text: "Check system health first.", next: "exit" }
            ]
        }
    ];

    let currentStep = 0;

    function typeWriter(text, i, cb) {
        if (i < text.length) {
            textElement.innerHTML = text.substring(0, i + 1) + '<span style="animation: blink 1s infinite">|</span>';
            setTimeout(() => typeWriter(text, i + 1, cb), 30);
        } else if (cb) {
            cb();
        }
    }

    function showStep(stepIndex) {
        optionsContainer.innerHTML = '';
        const step = script[stepIndex];
        
        // Animasi waifu muncul di step pertama
        if(stepIndex === 0) {
            setTimeout(() => {
                waifuImg.style.opacity = '1';
                waifuImg.style.transform = 'translateY(0)';
            }, 500);
        }

        typeWriter(step.text, 0, () => {
            step.options.forEach(opt => {
                const btn = document.createElement('button');
                btn.innerHTML = opt.text;
                btn.className = "btn btn-outline-info btn-sm fw-bold animate__animated animate__fadeInUp";
                btn.style = "border-width: 2px; letter-spacing: 1px;";
                btn.onclick = () => {
                    if (opt.next === "exit") {
                        exitIntro();
                    } else {
                        showStep(opt.next);
                    }
                };
                optionsContainer.appendChild(btn);
            });
        });
    }

    function exitIntro() {
        const overlay = document.getElementById('waifu-overlay');
        overlay.style.opacity = '0';
        sessionStorage.setItem('introDone', 'true');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 1000);
    }

    showStep(0);
});
</script>

<style>
@keyframes blink { 0% { opacity:1; } 50% { opacity:0; } 100% { opacity:1; } }
#waifu-overlay .btn-outline-info:hover {
    background: #00d2ff;
    color: #000;
    box-shadow: 0 0 15px #00d2ff;
}
</style>

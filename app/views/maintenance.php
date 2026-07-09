<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pro Animated Landing Page</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

  * {margin:0; padding:0; box-sizing:border-box;}
  html, body {height:100%; width:100%; font-family:'Poppins', sans-serif;}

  body {
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    background:#0f172a;
    color:white;
    overflow:hidden;
    padding:20px;
  }

  /* --- Background Code Animation --- */
  .code-bg {
    position:absolute;
    top:0; left:0;
    width:100%;
    height:100%;
    z-index:-1;
    overflow:hidden;
    background:#0f172a;
    font-family: 'Fira Code', monospace;
    font-size:14px;
    line-height:20px;
    color:#94a3b8;
    opacity:0.15;
  }

  .code-scroll {
    position:absolute;
    top:100%;
    white-space:pre;
    animation: scrollCode 50s linear infinite;
  }

  @keyframes scrollCode {
    0% { top:100%; }
    100% { top:-250%; }
  }

  /* Syntax Colors */
  .keyword { color:#38bdf8; }
  .func { color:#facc15; }
  .string { color:#34d399; }
  .var { color:#f472b6; }
  .comment { color:#64748b; font-style:italic; }

  /* Floating Gradient Pocket */
  .pocket {
    position: absolute;
    width:60px;
    height:60px;
    border-radius:50%;
    background: linear-gradient(45deg,#ff00ff,#00ffff);
    animation: floatPocket 8s ease-in-out infinite;
    z-index: 2;
    filter: blur(2px);
  }
  @keyframes floatPocket {
    0% { transform: translate(0,0) scale(1);}
    50% { transform: translate(150px,-80px) scale(1.3);}
    100% { transform: translate(0,0) scale(1);}
  }

  /* --- Header Title --- */
  h1 {
    font-size:clamp(28px,6vw,60px);
    text-align:center;
    background: linear-gradient(90deg, #00ffff, #ff00ff, #fcd34d);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: titleAnim 2s ease forwards;
    opacity:0;
  }
  @keyframes titleAnim {
    0% { transform: translateY(-50px); opacity:0;}
    100% { transform: translateY(0); opacity:1;}
  }

  /* --- Center Image Animation --- */
  .center-img {
    width:100%;
    max-width:600px;
    margin:30px 0;
    border-radius:20px;
    box-shadow: 0 0 40px rgba(255,255,255,0.3);
    opacity:0;
    animation: imgAnim 2.5s ease forwards, floatImg 6s ease-in-out infinite, pulseImg 8s infinite alternate;
  }
  @keyframes imgAnim {
    0% { transform: scale(0.5); opacity:0;}
    100% { transform: scale(1); opacity:1;}
  }
  @keyframes floatImg {
    0%,100% { transform: translateY(0);}
    50% { transform: translateY(-15px);}
  }
  @keyframes pulseImg {
    0% { box-shadow:0 0 20px rgba(0,255,255,0.4);}
    100% { box-shadow:0 0 50px rgba(255,0,255,0.6);}
  }

  /* --- Paragraph --- */
  p {
    font-size:clamp(16px,2vw,20px);
    color:#94a3b8;
    max-width:700px;
    text-align:center;
    margin-bottom:30px;
    opacity:0;
    animation: paraAnim 2.5s ease forwards;
    animation-delay:1.5s;
  }
  @keyframes paraAnim {
    0% { transform: translateY(20px); opacity:0;}
    100% { transform: translateY(0); opacity:1;}
  }

  /* --- Button Animation --- */
  .btn {
    padding:15px 40px;
    font-size:clamp(16px,2vw,18px);
    font-weight:600;
    color:white;
    border:none;
    border-radius:40px;
    cursor:pointer;
    opacity:0;
    animation: btnAnim 2s ease forwards, gradientShift 6s ease infinite, btnGlow 4s infinite alternate;
    animation-delay:2.5s;
    background: linear-gradient(270deg,#ff00ff,#00ffff,#ffcc00,#ff00ff);
    background-size: 400% 400%;
    position: relative;
    z-index: 1;
  }

  @keyframes btnAnim {
    0% { transform: translateY(20px); opacity:0;}
    100% { transform: translateY(0); opacity:1;}
  }

  @keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  @keyframes btnGlow {
    0% { box-shadow:0 0 15px rgba(0,255,255,0.4), 0 0 30px rgba(255,0,255,0.3); }
    100% { box-shadow:0 0 35px rgba(255,0,255,0.6), 0 0 60px rgba(0,255,255,0.5); }
  }

  .btn:hover {
    transform: scale(1.1);
  }
</style>
</head>
<body>

<!-- Background -->
<div class="code-bg">
  <div class="code-scroll">
<span class="comment">// Example React App</span><br>
<span class="keyword">import</span> React <span class="keyword">from</span> <span class="string">'react'</span>;<br>
<span class="keyword">import</span> Head <span class="keyword">from</span> <span class="string">'next/head'</span>;<br>
<span class="keyword">import</span> { useState } <span class="keyword">from</span> <span class="string">'react'</span>;<br>
<br>
<span class="keyword">function</span> <span class="func">App</span>() {<br>
&nbsp;&nbsp;<span class="keyword">const</span> [theme, setTheme] = <span class="func">useState</span>(<span class="string">'dark'</span>);<br>
&nbsp;&nbsp;<span class="keyword">return</span> (<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;<span class="func">div</span>&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;<span class="func">h1</span>&gt;Hello World&lt;/<span class="func">h1</span>&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/<span class="func">div</span>&gt;<br>
&nbsp;&nbsp;);<br>
}<br>
<br>
<span class="keyword">export default</span> App;<br>
  </div>
  <div class="pocket"></div>
</div>

<!-- Header -->
<h1>Magic Coming Soon</h1>

<!-- Center Image -->
<img class="center-img" src="https://assets-v2.lottiefiles.com/a/2e28fc3e-f8bc-11ee-ba1e-c7c5661e6375/JhYN2SGPau.gif" alt="Center Image">

<!-- Paragraph -->
<p>We’re working on exciting new features. Stay tuned for something amazing!</p>

<!-- Button -->
<button class="btn" onclick="window.open('https://wa.me/8801978158369','_blank');">Get Started</button>

</body>
</html>

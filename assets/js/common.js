// Minimal SABORES360 common helpers
window.SABORES360 = window.SABORES360 || {};
SABORES360.API_BASE = "http://localhost:8000/api/"; // adjust if needed

// API Configuration Debug Helper
SABORES360.Debug = {
  showAPIInfo: () => {
    const info = {
      "URL Base de API": SABORES360.API_BASE,
      "Puerto Actual":
        SABORES360.API_BASE.match(/:(\d+)/)?.[1] || "No detectado",
      Host: SABORES360.API_BASE.split("/")[2] || "No detectado",
      Protocolo: SABORES360.API_BASE.split(":")[0] || "No detectado",
      Timestamp: new Date().toLocaleString("es-ES"),
    };

    console.group("🔧 SABORES360 - Configuración de API");
    Object.entries(info).forEach(([key, value]) => {
      console.log(`${key}: %c${value}`, "color: #ff6b35; font-weight: bold;");
    });
    console.groupEnd();

    return info;
  },

  switchAPI: (port) => {
    const oldBase = SABORES360.API_BASE;
    SABORES360.API_BASE = `http://localhost:${port}/api/`;
    console.log(
      `🔄 API cambiada de %c${oldBase}%c a %c${SABORES360.API_BASE}`,
      "color: red;",
      "color: black;",
      "color: green; font-weight: bold;"
    );
    // Sync selection with server-side PHP via cookie so server-side auth checks use the same API base
    try {
      document.cookie =
        "SABORES_API_BASE=" +
        encodeURIComponent(SABORES360.API_BASE) +
        "; path=/";
    } catch (e) {}
    return SABORES360.API_BASE;
  },

  testConnection: async () => {
    console.log(
      `🧪 Probando conexión a: %c${SABORES360.API_BASE}`,
      "color: #ff6b35;"
    );
    try {
      const startTime = performance.now();
      const response = await fetch(
        SABORES360.API_BASE.replace("/api/", "/health"),
        {
          method: "GET",
          credentials: "include",
        }
      );
      const endTime = performance.now();
      const responseTime = Math.round(endTime - startTime);

      console.log(`✅ Conexión exitosa en ${responseTime}ms`);
      console.log(`Status: ${response.status} ${response.statusText}`);
      return { success: true, responseTime, status: response.status };
    } catch (error) {
      console.error(`❌ Error de conexión: ${error.message}`);
      return { success: false, error: error.message };
    }
  },
};

SABORES360.Notifications = {
  success: (msg) => {
    try {
      if (window.alert) {
        console.log("SUCCESS:", msg);
      }
    } catch (e) {}
  },
  error: (msg) => {
    try {
      if (window.alert) {
        alert(msg);
      }
    } catch (e) {}
  },
};

SABORES360.API = {
  request: async (method, path, body, opts = {}) => {
    const url = path.startsWith("http") ? path : SABORES360.API_BASE + path;
    const cfg = Object.assign({ method, credentials: "include" }, opts);
    if (body !== undefined && body !== null) {
      if (body instanceof FormData) {
        cfg.body = body; // browser will set content-type
      } else if (typeof body === "object") {
        cfg.headers = Object.assign(cfg.headers || {}, {
          "Content-Type": "application/json",
        });
        cfg.body = JSON.stringify(body);
      } else {
        cfg.body = body;
      }
    }
    // If there's an auth token stored as cookie, add Authorization header when not already set
    try {
      const cookies = document.cookie
        .split(";")
        .map((c) => c.trim())
        .filter(Boolean);
      for (const c of cookies) {
        if (c.startsWith("auth_token=")) {
          const token = decodeURIComponent(c.split("=")[1]);
          cfg.headers = Object.assign(cfg.headers || {}, {
            Authorization: "Bearer " + token,
          });
          break;
        }
      }
    } catch (e) {
      /* ignore */
    }

    console.debug("SABORES360.API request", method, url, cfg);

    // Log API calls with port information
    const port = url.match(/:(\d+)/)?.[1];
    console.log(`📡 API Call → Puerto ${port} | ${method} ${path}`, {
      fullURL: url,
      port: port,
      path: path,
      timestamp: new Date().toLocaleTimeString(),
    });

    const res = await fetch(url, cfg);
    const text = await res.text();
    try {
      return JSON.parse(text);
    } catch (e) {
      return { success: res.ok, httpStatus: res.status, raw: text };
    }
  },
  get: function (path, opts) {
    return this.request("GET", path, null, opts);
  },
  post: function (path, body, opts) {
    return this.request("POST", path, body, opts);
  },
  put: function (path, body, opts) {
    return this.request("PUT", path, body, opts);
  },
  delete: function (path, body, opts) {
    return this.request("DELETE", path, body, opts);
  },
};

// API Status Indicator - Visual indicator of which API is being used
SABORES360.createAPIIndicator = () => {
  // Remove existing indicator
  const existing = document.getElementById("api-indicator");
  if (existing) existing.remove();

  const port = SABORES360.API_BASE.match(/:(\d+)/)?.[1] || "?";
  const indicator = document.createElement("div");
  indicator.id = "api-indicator";
  indicator.innerHTML = `
    <div style="
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 9999;
      background: ${port === "8000" ? "#28a745" : "#ff6b35"};
      color: white;
      padding: 8px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      cursor: pointer;
      user-select: none;
      font-family: monospace;
    " onclick="SABORES360.Debug.showAPIInfo()" title="Click para ver detalles de la API">
      API :${port}
    </div>
  `;
  document.body.appendChild(indicator);
};

// Global logout handler: convert any <a class="logout-link"> into a server-side logout
document.addEventListener("click", function (e) {
  const el = e.target.closest && e.target.closest(".logout-link");
  if (!el) return;
  e.preventDefault();
  // prefer server-side logout.php to ensure cookies and session cleared
  window.location.href = "/Sabores360/logout.php";
});

// Initialize API indicator when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  SABORES360.createAPIIndicator();

  // Ensure server knows which API base the client is using
  try {
    document.cookie =
      "SABORES_API_BASE=" +
      encodeURIComponent(SABORES360.API_BASE) +
      "; path=/";
  } catch (e) {}

  // Show API info in console on page load
  setTimeout(() => {
    SABORES360.Debug.showAPIInfo();
  }, 500);
});

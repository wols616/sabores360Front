// Minimal SABORES360 common helpers
window.SABORES360 = window.SABORES360 || {};
SABORES360.API_BASE = "http://localhost:8080/api/"; // adjust if needed

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

// Global logout handler: convert any <a class="logout-link"> into a server-side logout
document.addEventListener("click", function (e) {
  const el = e.target.closest && e.target.closest(".logout-link");
  if (!el) return;
  e.preventDefault();
  // prefer server-side logout.php to ensure cookies and session cleared
  window.location.href = "/Sabores360/logout.php";
});

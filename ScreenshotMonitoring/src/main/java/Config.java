public class Config {
   static int SCREENSHOT_INTERVAL_MS = 6000;
   static float COMPRESSION_QUALITY = 0.1f;

   static String SERVER_URL = "https://screenshotmonitoring.bkopec.com/upload.php";
   static int MAX_FILES_PER_UPLOAD = 90;
   static int INTERVAL_PER_UPLOAD_MS = 160000;
   static String USER_DOCUMENTS_PATH = System.getProperty("user.home") + "\\Pictures\\ScreenshotMonitoring\\";
   static String key;
    static void init() {
       String os = System.getProperty("os.name").toLowerCase();

       if (!os.contains("win"))
         USER_DOCUMENTS_PATH = USER_DOCUMENTS_PATH.replace("\\", "/");

    }
}

import java.awt.Rectangle;
import java.awt.Robot;
import java.awt.Toolkit;
import java.awt.image.BufferedImage;
import java.io.File;
import javax.imageio.ImageIO;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.*;
import com.luciad.imageio.webp.WebPWriteParam;
import javax.imageio.ImageWriter;
import javax.imageio.IIOImage;
import javax.imageio.ImageIO;
import javax.imageio.ImageWriteParam;
import javax.imageio.stream.FileImageOutputStream;


public class Recorder {
    private Robot robot;
    private ImageWriter writer = ImageIO.getImageWritersByMIMEType("image/webp").next();
    private WebPWriteParam writeParam;

    private String userDocumentsPath = Config.USER_DOCUMENTS_PATH;


    public void setupWriter() throws Exception {
        writeParam = new WebPWriteParam(writer.getLocale());
        writeParam.setCompressionMode(ImageWriteParam.MODE_EXPLICIT);
        writeParam.setCompressionType(writeParam.getCompressionTypes()[WebPWriteParam.LOSSY_COMPRESSION]);
        writeParam.setCompressionQuality(Config.COMPRESSION_QUALITY);

        robot = new Robot();
        
        File folder = new File(userDocumentsPath);
        if (!folder.exists()) {
            boolean created = folder.mkdirs();
            if (created) {
                System.out.println("Folder created successfully: " + userDocumentsPath);
            } else {
                System.err.println("Failed to create folder: " + userDocumentsPath);
            }
        }
    }

    public void record() throws Exception
    {
        while(true) {
            FileImageOutputStream fileStream = new FileImageOutputStream(new File(userDocumentsPath + System.currentTimeMillis() + ".webp"));
            BufferedImage screenShot = robot.createScreenCapture(new Rectangle(Toolkit.getDefaultToolkit().getScreenSize()));
            writer.setOutput(fileStream);
            writer.write(null, new IIOImage(screenShot, null, null), writeParam);
            fileStream.close();
            screenShot = null;
            Thread.sleep(Config.SCREENSHOT_INTERVAL_MS);
        }
    }
}


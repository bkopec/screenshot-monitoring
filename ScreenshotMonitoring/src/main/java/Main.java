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

public class Main {
    public static void main(String[] args) throws Exception
    {
        Config.init();
        Sender s = new Sender();
        Recorder recorder = new Recorder();
        recorder.setupWriter();
        s.start();
        recorder.record();
    }
}
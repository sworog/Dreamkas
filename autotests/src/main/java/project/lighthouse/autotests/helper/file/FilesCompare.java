package project.lighthouse.autotests.helper.file;

import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.security.DigestInputStream;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Arrays;

public class FilesCompare {

    private File file1, file2;

    public FilesCompare(File file1, File file2) {
        this.file1 = file1;
        this.file2 = file2;
    }

    public Boolean compare() throws IOException, NoSuchAlgorithmException {
        String file1md5 = getMD5(file1);
        String file2md5 = getMD5(file2);
        return file1md5.equals(file2md5);
    }

    private String getMD5(File file) throws NoSuchAlgorithmException, IOException {
        MessageDigest md = MessageDigest.getInstance("MD5");
        try (InputStream is = Files.newInputStream(Paths.get(file.getPath()))) {
            DigestInputStream dis = new DigestInputStream(is, md);
        }
        return Arrays.toString(md.digest());
    }
}

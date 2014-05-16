package project.lighthouse.autotests.helper;

import java.io.File;
import java.io.IOException;
import java.io.RandomAccessFile;

/**
 * Class to create file with given size of megabytes
 */
public class FileCreator {

    private String fileName;
    private int size;

    private static final String PATH = System.getProperty("user.dir") + "/upload/";

    public FileCreator(String fileName, int size) {
        this.fileName = fileName;
        this.size = size * 1024;
    }

    private File createNewFile() throws IOException {
        new File(PATH).mkdirs();
        return new File(PATH + fileName);
    }

    public File create() {
        File file;
        RandomAccessFile randomAccessFile;
        try {
            file = createNewFile();
            randomAccessFile = new RandomAccessFile(file, "rw");
            randomAccessFile.setLength(size);
            randomAccessFile.close();
            return file;
        } catch (IOException e) {
            throw new AssertionError(e);
        }
    }
}

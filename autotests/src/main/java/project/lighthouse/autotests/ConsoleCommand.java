package project.lighthouse.autotests;

import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStreamReader;

public class ConsoleCommand {

    private String folder;
    private String host;

    public ConsoleCommand(String folder, String host) {
        this.folder = getAbsoluteFolderPath(folder);
        this.host = host;
    }

    private String getAbsoluteFolderPath(String folder) {
        return new File(System.getProperty("user.dir")).getParent().concat("\\").concat(folder);
    }

    public String exec(String command) throws IOException, InterruptedException {
        String cmd = cmd(command, host);
        File dir = new File(folder);
        Process process = Runtime.getRuntime().exec(cmd, null, dir);
        process.waitFor();
        return readOutput(process);
    }

    private String cmd(String command, String host) {
        return String.format("cmd /c \"%s -S host=%s\"", command, host);
    }

    private String readOutput(Process process) throws IOException {
        BufferedReader inputStream = new BufferedReader(
                new InputStreamReader(process.getInputStream())
        );
        BufferedReader errorStream = new BufferedReader(new
                InputStreamReader(process.getErrorStream()));
        String s;
        String output = "";
        while ((s = inputStream.readLine()) != null) {
            output = output.concat(s).concat("\n");
        }
        while ((s = errorStream.readLine()) != null) {
            output = output.concat(s).concat("\n");
        }
        return output;
    }
}

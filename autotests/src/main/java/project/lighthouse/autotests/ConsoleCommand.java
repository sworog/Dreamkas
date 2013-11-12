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
        return System.getProperty("user.dir").concat("/").concat(folder);
    }

    public String exec(String command) throws IOException, InterruptedException {
        String cmd = cmd(command, host);
        File dir = new File(folder);
        Process process = Runtime.getRuntime().exec(cmd, null, dir);
        process.waitFor();
        return readOutput(process);
    }

    private String cmd(String command, String host) {
        return String.format("%s -S host=%s", command, host);
    }

    private String readOutput(Process process) throws IOException {
        BufferedReader reader = new BufferedReader(
            new InputStreamReader(process.getInputStream())
        );
        String output = new String();
        String line = reader.readLine();
        while (line != null) {
            output = output.concat(stripAnsiCodes(line)).concat("\n");
            line = reader.readLine();
        }
        return output;
    }

    private String stripAnsiCodes(String input) {
        return input.replaceAll("\u001B\\[[;\\d]*m", "");
    }
}

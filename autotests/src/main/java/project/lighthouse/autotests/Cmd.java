package project.lighthouse.autotests;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

public class Cmd {

    public void exec(String command) {
        try {
            String CmdCommand = String.format("cmd /c \"cd ../backend && %s -S host=%s\"", command, System.getProperty("init"));
            Process p = Runtime.getRuntime().exec(CmdCommand);
            p.waitFor();
            BufferedReader reader = new BufferedReader(
                    new InputStreamReader(p.getInputStream())
            );
            String line = reader.readLine();
            while (line != null) {
                System.out.println(line);
                line = reader.readLine();
            }
        } catch (IOException | InterruptedException ignored) {
        }
    }
}

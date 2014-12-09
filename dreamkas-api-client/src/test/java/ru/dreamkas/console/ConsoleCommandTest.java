package ru.dreamkas.console;

import org.hamcrest.Matchers;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

import java.io.File;
import java.io.IOException;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ConsoleCommandTest {

    private String osName;

    @Before
    public void before() {
        osName = System.getProperty("os.name");
    }

    @Test
    public void testCommandExecutionSuccess() throws IOException, InterruptedException {
        String pathToFolder = new File(System.getProperty("user.dir")).getParent().concat(File.separator).concat("folder");
        new File(pathToFolder).mkdir();
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand("/folder", "host").exec("echo 1");
        assertThat(consoleCommandResult.isOk(), is(true));
        assertThat(consoleCommandResult.getOutput(), is("1 -S host=host -S branch=master\n"));
    }

    @Test
    public void testCommandExecutionFail() throws IOException, InterruptedException {
        String pathToFolder = new File(System.getProperty("user.dir")).getParent().concat(File.separator).concat("folder");
        new File(pathToFolder).mkdir();
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand("/folder", "host").exec("abracadabra");
        assertThat(consoleCommandResult.isOk(), is(false));
        assertThat(consoleCommandResult.getOutput(), is("/bin/bash: abracadabra: command not found\n"));
    }

    @Test
    public void testUnixCmdOutPut() throws IOException, InterruptedException {
        System.setProperty("os.name", "Unix");
        assertThat(
                new ConsoleCommand("/folder", "host").cmd("command"),
                Matchers.is(new String[]{"/bin/bash", "-c", "command -S host=host -S branch=master"}));
    }

    @Test
    public void testWindowsCmdOutPut() throws IOException, InterruptedException {
        System.setProperty("os.name", "windows");
        assertThat(
                new ConsoleCommand("/folder", "host").cmd("command"),
                Matchers.is(new String[]{"cmd", "/c", "command -S host=host -S branch=master"}));
    }

    @Test
    public void testMacCmdOutPut() throws IOException, InterruptedException {
        System.setProperty("os.name", "mac");
        assertThat(
                new ConsoleCommand("/folder", "host").cmd("command"),
                Matchers.is(new String[]{"/bin/bash", "-c", "LC_ALL=en_US.UTF-8 command -S host=host -S branch=master"}));
    }

    @Test
    public void testConsoleCommandIsWindows() {
        System.setProperty("os.name", "windows");
        assertThat(ConsoleCommand.isWindows(), is(true));
    }

    @Test
    public void testConsoleCommandIsMac() {
        System.setProperty("os.name", "Mac");
        assertThat(ConsoleCommand.isMac(), is(true));
    }

    @Test
    public void testConsoleCommandIsUnix() {
        System.setProperty("os.name", "Unix");
        assertThat(ConsoleCommand.isUnix(), is(true));
    }

    @Test
    public void testConsoleCommandIsLinux() {
        System.setProperty("os.name", "linux");
        assertThat(ConsoleCommand.isUnix(), is(true));
    }

    @Test
    public void testConsoleCommandIsNotLinux() {
        System.setProperty("os.name", "abracadabra");
        assertThat(ConsoleCommand.isUnix(), is(false));
    }

    @After
    public void after() {
        System.setProperty("os.name", osName);
    }
}

package ru.dreamkas.unit.console;

import org.junit.After;
import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;
import org.mockito.Mock;
import org.mockito.Mockito;
import ru.dreamkas.console.ConsoleCommand;
import ru.dreamkas.console.ConsoleCommandResult;

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
    public void testCommandExecution() throws IOException, InterruptedException {
        String pathToFolder = new File(System.getProperty("user.dir")).getParent().concat(File.separator).concat("folder");
        new File(pathToFolder).mkdir();
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand("/folder", "host").exec("echo 1");
        assertThat(consoleCommandResult.isOk(), is(true));
        assertThat(consoleCommandResult.getOutput(), is("1 -S host=host -S branch=master\n"));
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

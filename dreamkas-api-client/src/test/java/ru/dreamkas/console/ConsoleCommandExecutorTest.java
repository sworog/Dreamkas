package ru.dreamkas.console;


import org.junit.After;
import org.junit.Before;
import org.junit.Rule;
import org.junit.Test;
import org.junit.rules.ExpectedException;
import org.mockito.Mockito;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.console.exception.ConsoleCommandFailureException;

import java.io.File;
import java.io.IOException;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ConsoleCommandExecutorTest {

    @Rule
    public ExpectedException expectedException = ExpectedException.none();

    private ConsoleCommandExecutor consoleCommandExecutor;

    @Before
    public void before() {
        System.setProperty("api.staging", "autotests");
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", "http://test.autotests.webfront.lighthouse.pro");
        String pathToFolder = new File(System.getProperty("user.dir")).getParent().concat(File.separator).concat("folder");
        new File(pathToFolder).mkdir();
        consoleCommandExecutor = Mockito.spy(new ConsoleCommandExecutor("echo 1", "/folder"));
    }

    @Test
    public void testRunSuccess() throws IOException, InterruptedException {
        Mockito.doReturn("echo 1").when(consoleCommandExecutor).getCommandToExecute();
        ConsoleCommandResult consoleCommandResult = consoleCommandExecutor.run();
        assertThat(consoleCommandResult.isOk(), is(true));
        assertThat(consoleCommandResult.getOutput(), is("1 -S host=test -S branch=master\n"));
    }

    @Test(expected = ConsoleCommandFailureException.class)
    public void testRunFail() throws IOException, InterruptedException {
        Mockito.doReturn("abracadabra").when(consoleCommandExecutor).getCommandToExecute();
        consoleCommandExecutor.run();
    }

    @Test
    public void testGetCommandToExecute() {
        System.setProperty("api.staging", "autotests");
        assertThat(
                new ConsoleCommandExecutor("command", "/folder").getCommandToExecute(),
                is("bundle exec cap autotests log:debug command"));

    }

    @After
    public void after() {
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", null);
        System.setProperty("api.staging", "");
    }
}

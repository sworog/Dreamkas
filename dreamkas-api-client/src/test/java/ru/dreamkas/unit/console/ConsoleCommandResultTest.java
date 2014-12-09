package ru.dreamkas.unit.console;

import org.junit.Test;
import ru.dreamkas.console.ConsoleCommandResult;

import static org.junit.Assert.*;
import static org.hamcrest.Matchers.is;

public class ConsoleCommandResultTest {

    @Test
    public void testConsoleCommandResultOutPutGetter() {
        assertThat(new ConsoleCommandResult(0, "test").getOutput(), is("test"));
    }

    @Test
    public void testConsoleCommandResultExitCodeGetterIsOk() {
        assertThat(new ConsoleCommandResult(0, "test").isOk(), is(true));
    }

    @Test
    public void testConsoleCommandResultExitCodeGetterIsNotOk() {
        assertThat(new ConsoleCommandResult(1, "test").isOk(), is(false));
    }
}

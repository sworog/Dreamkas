package project.lighthouse.autotests.steps;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.console.ConsoleCommand;
import project.lighthouse.autotests.console.ConsoleCommandResult;

import java.io.IOException;

public class ConsoleCommandSteps extends ScenarioSteps {

    public ConsoleCommandSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void runConsoleCommand(String command, String folder) throws IOException, InterruptedException {
        String host = StaticData.WEB_DRIVER_BASE_URL.replaceAll("http://(.*).(.*).webfront.lighthouse.cs", "$1");
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand(folder, host).exec(command);
        if (!consoleCommandResult.isOk()) {
            Assert.fail(consoleCommandResult.getOutput());
        }
    }
}

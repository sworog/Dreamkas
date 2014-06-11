package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import java.io.IOException;

public class ConsoleCommandsUserSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("the user runs the recalculate_metrics cap command")
    public void givenTheRobotRunsTheRecalculateMetricsCapCommand() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyProductsRecalculateMetricsCommand();
    }

    @Given("the user runs the symfony:env:init command")
    public void givenTheUserRunsTheSymfonyEnvInitCommand() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestSymfonyEnvInitCommand();
        StaticData.clear();
    }

    @Given("the user runs the symfony:reports:recalculate command")
    public void givenTheUserRunTheSymfonyReportsRecalculateCommand() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyReportsRecalculateCommand();
    }

    @Given("the user runs the symfony:user:create command with params: email '$email' and password '$password'")
    public void givenTheUserRunsTheSymfonyUserCreateCommandWithParams(String email, String password) throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyCreateUserCommand(email, password);
    }

    @Given("the user runs the symfony:user:create command with params: generated email and common password")
    public void givenTheUserRunsTheSymfonyUserCreateCommandWithParams() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyCreateUserCommandWithEmailGeneratedAndCommonPassword();
    }
}

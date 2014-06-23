package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.console.ConsoleCommandResult;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

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
    public void givenTheUserRunsTheSymfonyUserCreateCommandWithParams(String email, String password) throws IOException, InterruptedException, JSONException {
        ConsoleCommandResult consoleCommandResult = consoleCommandSteps.runCapAutoTestsSymfonyCreateUserCommand(email, password);
        UserContainer userContainer = getUserContainer(consoleCommandResult);
        Storage.getUserVariableStorage().getUserContainers().add(userContainer);
        Storage.getUserVariableStorage().getUserContainers().getContainer(email).setPassword(password);
    }

    private UserContainer getUserContainer(ConsoleCommandResult consoleCommandResult) throws JSONException {
        //WORKAROUND
        return new UserContainer(new JSONObject(consoleCommandResult.getOutput().split("\n")[6]));

        //FIXME this code don't work
//        Pattern pattern = Pattern.compile("(\\{.*\\}\\})");
//        Matcher matcher = pattern.matcher(consoleCommandResult.getOutput().toString());
//        if(matcher.matches()) {
//            return new UserContainer(new JSONObject(matcher.group(1)));
//        } else {
//            throw new AssertionError("No matches with pattern");
//        }
    }

    @Given("the user runs the symfony:user:create command with params: generated email and common password")
    public void givenTheUserRunsTheSymfonyUserCreateCommandWithParams() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyCreateUserCommandWithEmailGeneratedAndCommonPassword();
    }
}

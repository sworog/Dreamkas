package ru.dreamkas.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import org.json.JSONObject;
import ru.dreamkas.console.ConsoleCommandResult;
import ru.dreamkas.steps.console.ConsoleCommandSteps;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.containers.user.UserContainer;
import ru.dreamkas.apiStorage.containers.user.UserContainerList;

import java.io.IOException;

public class GivenConsoleCommandsUserSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("пользователь запускает команду пересчета метрики продуктов")
    public void givenTheRobotRunsTheRecalculateMetricsCapCommand() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyProductsRecalculateMetricsCommand();
    }

    @Given("the user runs the symfony:env:init command")
    @Alias("пользователь запускает консольную команду для очищения всех данных")
    public void givenTheUserRunsTheSymfonyEnvInitCommand() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestSymfonyEnvInitCommand();
        ApiStorage.getStorageClearable().clear();
    }

    @Given("пользователь запускает команду пересчета отчетов")
    public void givenTheUserRunTheSymfonyReportsRecalculateCommand() throws IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyReportsRecalculateCommand();
    }

    @Given("the user runs the symfony:user:create command with params: email '$email' and password '$password'")
    @Alias("пользователь запускает консольную команду для создания пользователя с параметрами: адрес электронной почты '$email' и пароль '$password'")
    public void givenTheUserRunsTheSymfonyUserCreateCommandWithParams(String email, String password) throws IOException, InterruptedException, JSONException {
        UserContainerList userContainers = ApiStorage.getUserVariableStorage().getUserContainers();
        if (!userContainers.hasContainerWithEmail(email)) {
            ConsoleCommandResult consoleCommandResult = consoleCommandSteps.runCapAutoTestsSymfonyCreateUserCommand(email, password);
            UserContainer userContainer = getUserContainer(consoleCommandResult);
            ApiStorage.getUserVariableStorage().getUserContainers().add(userContainer);
            ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email).setPassword(password);
        }
    }

    private UserContainer getUserContainer(ConsoleCommandResult consoleCommandResult) throws JSONException {
        //WORKAROUND
        for (String resultRow : consoleCommandResult.getOutput().split("\n")) {
            if (resultRow.startsWith("{")) {
                return new UserContainer(new JSONObject(resultRow));
            }
        }

        throw new AssertionError("No user json found in response");

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

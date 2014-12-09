package ru.dreamkas.steps.console;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.console.ConsoleCommandResult;
import ru.dreamkas.console.backend.*;
import ru.dreamkas.apihelper.UUIDGenerator;
import ru.dreamkas.apiStorage.ApiStorage;

import java.io.File;
import java.io.IOException;

public class ConsoleCommandSteps extends ScenarioSteps {

    @Step
    public void runCapAutoTestsSymfonyImportSalesLocalCommand(String filePath) throws IOException, InterruptedException {
        new SymfonyImportSalesLocalConsoleCommand(filePath).run();
    }

    @Step
    public void runCapAutoTestsSymfonyImportSalesLocalCommand(File file) throws IOException, InterruptedException {
        runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }

    @Step
    public void runCapAutoTestsSymfonyProductsRecalculateMetricsCommand() throws IOException, InterruptedException {
        new SymfonyProductsRecalculateMetricsConsoleCommand().run();
    }

    @Step
    public void runCapAutoTestSymfonyEnvInitCommand() throws IOException, InterruptedException {
        new SymfonyEnvInitConsoleCommand().run();
    }

    @Step
    public void runCapAutoTestsSymfonyReportsRecalculateCommand() throws IOException, InterruptedException {
        new SymfonyReportsRecalculateConsoleCommand().run();
    }

    @Step
    public ConsoleCommandResult runCapAutoTestsSymfonyCreateUserCommand(String email, String password) throws IOException, InterruptedException, JSONException {
        return new SymfonyUserCreateConsoleCommand(email, password).run();
    }

    @Step
    public void runCapAutoTestsSymfonyCreateUserCommandWithEmailGeneratedAndCommonPassword() throws IOException, InterruptedException {
        String generatedEmail = String.format("%s@lighthouse.pro", new UUIDGenerator().generate());
        ApiStorage.getCustomVariableStorage().setEmail(generatedEmail);
        new SymfonyUserCreateConsoleCommand(generatedEmail, "lighthouse").run();
    }
}

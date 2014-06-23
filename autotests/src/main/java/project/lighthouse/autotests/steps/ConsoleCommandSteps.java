package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.console.ConsoleCommandResult;
import project.lighthouse.autotests.console.backend.*;
import project.lighthouse.autotests.helper.UUIDGenerator;
import project.lighthouse.autotests.storage.Storage;

import java.io.File;
import java.io.IOException;

public class ConsoleCommandSteps extends ScenarioSteps {

    @Step
    public void runCapAutoTestsSymfonyImportSalesLocalCommand(String filePath) throws IOException, InterruptedException {
        new SymfonyImportSalesLocalCommand(filePath).run();
    }

    @Step
    public void runCapAutoTestsSymfonyImportSalesLocalCommand(File file) throws IOException, InterruptedException {
        runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }

    @Step
    public void runCapAutoTestsSymfonyProductsRecalculateMetricsCommand() throws IOException, InterruptedException {
        new SymfonyProductsRecalculateMetricsCommand().run();
    }

    @Step
    public void runCapAutoTestSymfonyEnvInitCommand() throws IOException, InterruptedException {
        new SymfonyEnvInitCommand().run();
    }

    @Step
    public void runCapAutoTestsSymfonyReportsRecalculateCommand() throws IOException, InterruptedException {
        new SymfonyReportsRecalculateCommand().run();
    }

    @Step
    public ConsoleCommandResult runCapAutoTestsSymfonyCreateUserCommand(String email, String password) throws IOException, InterruptedException, JSONException {
        return new SymfonyUserCreateCommand(email, password).run();
    }

    @Step
    public void runCapAutoTestsSymfonyCreateUserCommandWithEmailGeneratedAndCommonPassword() throws IOException, InterruptedException {
        String generatedEmail = String.format("%s@lighthouse.pro", new UUIDGenerator().generate());
        Storage.getCustomVariableStorage().setEmail(generatedEmail);
        new SymfonyUserCreateCommand(generatedEmail, "lighthouse").run();
    }
}

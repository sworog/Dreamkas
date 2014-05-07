package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.xml.sax.SAXException;
import project.lighthouse.autotests.console.backend.SymfonyEnvInitCommand;
import project.lighthouse.autotests.console.backend.SymfonyImportSalesLocalCommand;
import project.lighthouse.autotests.console.backend.SymfonyProductsRecalculateMetricsCommand;
import project.lighthouse.autotests.console.backend.SymfonyReportsRecalculateCommand;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.XmlReplacement;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
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
}

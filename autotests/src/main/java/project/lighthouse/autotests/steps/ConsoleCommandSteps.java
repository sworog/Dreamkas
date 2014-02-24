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
    public void runFixtureCommand() throws IOException, InterruptedException, TransformerException, ParserConfigurationException, SAXException {
        //TODO refactor
        String directoryPath = System.getProperty("user.dir") + "/xml/fixtures/sales";
        File patternFile = new File(directoryPath + "/salesPattern.xml");
        String filePath = directoryPath + "/sales.xml";
        new XmlReplacement(patternFile).createFile(new DateTimeHelper("today-5days").convertDate(), new File(filePath));
        new SymfonyImportSalesLocalCommand(filePath).run();
    }

    @Step
    public void runNegativeFixtureCommand(String days) throws IOException, InterruptedException, TransformerException, ParserConfigurationException, SAXException {
        //TODO refactor
        String directoryPath = System.getProperty("user.dir") + "/xml/fixtures/sales";
        File patternFile = new File(directoryPath + "/negativeSalesPattern.xml");
        String filePath = directoryPath + "/negativeSales.xml";
        new XmlReplacement(patternFile).createFile(new DateTimeHelper("today-" + days + "days").convertDate(), new File(filePath));
        new SymfonyImportSalesLocalCommand(filePath).run();
    }

    @Step
    public void runNegativeFixtureCommand2(String days) throws IOException, InterruptedException, TransformerException, ParserConfigurationException, SAXException {
        //TODO refactor
        String directoryPath = System.getProperty("user.dir") + "/xml/fixtures/sales";
        File patternFile = new File(directoryPath + "/negativeSalesPattern1.xml");
        String filePath = directoryPath + "/negativeSales.xml";
        new XmlReplacement(patternFile).createFile(new DateTimeHelper("today-" + days + "days").convertDate(), new File(filePath));
        new SymfonyImportSalesLocalCommand(filePath).run();
    }

    @Step
    public void runCapAutoTestsSymfonyImportSalesLocalCommand(String filePath) throws IOException, InterruptedException {
        new SymfonyImportSalesLocalCommand(filePath).run();
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

package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.PostgreSQLJDBC;

import java.sql.SQLException;

public class PostgreSQLJDBCSteps extends ScenarioSteps {

    public PostgreSQLJDBCSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void truncateProductsTable(String ip) throws SQLException {
        new PostgreSQLJDBC(ip).truncateTable();
    }

    @Step
    public void waitUntilTableIsNotEmpty(String ip) throws SQLException, InterruptedException {
        PostgreSQLJDBC postgreSQLJDBC = new PostgreSQLJDBC(ip);
        Boolean tableHasEmptyRows = postgreSQLJDBC.tableHasEmptyRows();
        while (!tableHasEmptyRows) {
            Thread.sleep(1000);
            tableHasEmptyRows = postgreSQLJDBC.tableHasEmptyRows();
        }
    }
}

package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.PostgreSQLJDBC;

import java.sql.SQLException;

import static junit.framework.Assert.fail;

public class PostgreSQLJDBCSteps extends ScenarioSteps {

    @Step
    public void truncateProductsTable(String ip) throws SQLException {
        new PostgreSQLJDBC(ip).truncateTable();
    }

    @Step
    public void waitUntilTableIsNotEmpty(String ip) throws SQLException, InterruptedException {
        PostgreSQLJDBC postgreSQLJDBC = new PostgreSQLJDBC(ip);
        Boolean tableHasEmptyRows = postgreSQLJDBC.tableHasEmptyRows();
        int count = 0;
        while (!tableHasEmptyRows && count < 100) {
            Thread.sleep(1000);
            tableHasEmptyRows = postgreSQLJDBC.tableHasEmptyRows();
            count++;
        }
        if (!tableHasEmptyRows && count == 100) {
            fail("The table is still empty after timeout!");
        }
    }
}

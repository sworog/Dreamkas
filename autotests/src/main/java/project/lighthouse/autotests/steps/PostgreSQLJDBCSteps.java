package project.lighthouse.autotests.steps;

import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.PostgreSQLJDBC;

import java.sql.SQLException;

public class PostgreSQLJDBCSteps extends ScenarioSteps {

    PostgreSQLJDBC postgreSQLJDBC;

    public PostgreSQLJDBCSteps(Pages pages) {
        super(pages);
    }

    public void truncateProductsTable(String ip) throws SQLException {
        new PostgreSQLJDBC(ip).truncateTable();
    }
}

package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.PostgreSQLJDBCSteps;

import java.sql.SQLException;

public class PostgreSQLJDBCUserSteps {

    @Steps
    PostgreSQLJDBCSteps postgreSQLJDBCSteps;

    @Given("the user cleans the cash db by '$ip' ip")
    public void givenTheUserCleansTheCashDB(String ip) throws SQLException {
        postgreSQLJDBCSteps.truncateProductsTable(ip);
    }

    @Given("the user waits until cash registry by '$ip' products db has not empty rows")
    public void givenTheUSerWaitsUntilCashProductDBHasNotEmptyRows(String ip) throws SQLException, InterruptedException {
        postgreSQLJDBCSteps.waitUntilTableIsNotEmpty(ip);
    }
}

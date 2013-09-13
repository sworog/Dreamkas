package project.lighthouse.autotests;

import junit.framework.Assert;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class PostgreSQLJDBC {

    String ip;
    private static final String USENAME = "postgres";
    private static final String PASSWORD = "postgres";

    public PostgreSQLJDBC(String ip) {
        this.ip = ip;
    }

    public void truncateTable() throws SQLException {
        Connection connection = null;
        try {
            Class.forName("org.postgresql.Driver");
            String connectionUrl = String.format("jdbc:postgresql://%s/catalog", this.ip);

            connection = DriverManager
                    .getConnection(connectionUrl,
                            USENAME, PASSWORD);
            connection.createStatement().execute("TRUNCATE cg_product CASCADE;");
        } catch (Exception e) {
            Assert.fail(e.getMessage());
        } finally {
            if (connection != null) {
                connection.close();
            }
        }
    }
}

package project.lighthouse.autotests;

import junit.framework.Assert;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class PostgreSQLJDBC {

    String ip;
    private static final String USENAME = "postgres";
    private static final String PASSWORD = "postgres";

    Connection connection;

    public PostgreSQLJDBC(String ip) {
        this.ip = ip;
    }

    public Boolean tableHasEmptyRows() throws SQLException {
        try {
            getConnection();
            return connection.createStatement().executeQuery("Select * from cg_product").next();
        } catch (Exception e) {
            Assert.fail(e.getMessage());
        } finally {
            closeConnection();
        }
        return false;
    }

    private void getConnection() throws SQLException, ClassNotFoundException {
        Class.forName("org.postgresql.Driver");
        String connectionUrl = String.format("jdbc:postgresql://%s/catalog", this.ip);
        connection = DriverManager
                .getConnection(connectionUrl,
                        USENAME, PASSWORD);
    }

    private void closeConnection() throws SQLException {
        if (connection != null) {
            connection.close();
        }
    }

    public void truncateTable() throws SQLException {
        try {
            getConnection();
            connection.createStatement().execute("TRUNCATE cg_product CASCADE;");
        } catch (Exception e) {
            Assert.fail(e.getMessage());
        } finally {
            closeConnection();
        }
    }
}

package ru.dreamkas.pos.unit.model;

import android.test.InstrumentationTestCase;

import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.model.api.Product;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class ReceiptTest extends InstrumentationTestCase {
    private Receipt mReceipt;

    @Override
    protected void setUp() throws Exception {
        super.setUp();

        mReceipt = new Receipt();
    }

    public void testReceiptFill() {

        Product product = new Product();
        Product product2 = new Product();

        mReceipt.add(product);
        mReceipt.add(product2);

        assertThat("Wrong receipt size after products add", mReceipt.size(), is(2));
    }

    public void testReceiptClear() {

        Product product = new Product();
        Product product2 = new Product();

        mReceipt.add(product);
        mReceipt.add(product2);

        mReceipt.clear();

        assertThat("Receipt should be empty after clear", mReceipt.size(), is(0));
    }

    public void testReceiptTotal() {

        Product product = new Product();
        product.setSellingPrice(150);

        Product product2 = new Product();
        product2.setSellingPrice(100);

        Product product3 = new Product();
        product3.setSellingPrice(23);

        mReceipt.add(product);
        mReceipt.add(product2);
        mReceipt.add(product3);

        mReceipt.clear();

        assertThat("Wrong receipt total", mReceipt.getTotal(), is(273));
    }

}

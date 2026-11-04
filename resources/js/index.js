import { loadConnectAndInitialize } from "@stripe/connect-js";

const fetchClientSecret = async () => {
  return "accs_secret__T93w902Ybmjox8ctVlHIJ23eXPB4E4e7EXnC3swLWVRzuiW";
};

(async () => {
  const stripeConnectInstance = await loadConnectAndInitialize({
    publishableKey:
      "pk_test_51MP68aCWTG6yySfZu24ZEWKqmDwN0fDK9i2yGujMi2w9FtK1sY01AiTeb0PYpMiRFUlsjriBlgHICd3ewikNRRoL00IDnCrigU",
    fetchClientSecret,
  });

  const paymentComponent = stripeConnectInstance.create("payments");
  document.getElementById("container").appendChild(paymentComponent);
})();
